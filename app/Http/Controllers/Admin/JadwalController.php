<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Day;
use App\Models\Jadwal;
use App\Models\Room;
use App\Models\Time;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function all(Request $request)
    {

        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchLecturers = $request->input('searchlecturers');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

        // Filter berdasarkan nama hari jika searchlecturers tidak kosong
        if (!empty($searchDays)) {
            $query->whereHas('day', function ($lecturerQuery) use ($searchDays) {
                $lecturerQuery->where('name_day', 'LIKE', '%' . $searchDays . '%');
            });
        }

        // Filter berdasarkan nama guru jika searchlecturers tidak kosong
        if (!empty($searchLecturers)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchLecturers) {
                $teachQuery->whereHas('lecturer', function ($courseQuery) use ($searchLecturers) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchLecturers . '%');
                });
            });
        }

        // Filter berdasarkan nama mata Pelajaran jika searchcourse tidak kosong
        if (!empty($searchCourse)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchCourse) {
                $teachQuery->whereHas('course', function ($courseQuery) use ($searchCourse) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchCourse . '%');
                });
            });
        }

        // Filter berdasarkan nama kelas jika searchclass tidak kosong
        if (!empty($searchClass)) {
            $query->whereHas('teach', function ($lecturerQuery) use ($searchClass) {
                $lecturerQuery->where('class_room', 'LIKE', '%' . $searchClass . '%');
            });
        }
        // End Bagian search Data //


        $schedules = $query->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();
        return view('admin.jadwal.jadwal_all', compact('day', 'time', 'room', 'schedules'));
    }

    public function updatejadwal(Request $request, $id)
    {

        $this->validate($request, [
            'days_id' => 'required',
            'times_id' => 'required',
            'rooms_id' => 'required',
            // Tambahkan validasi lain sesuai kebutuhan Anda
        ]);

        // Mengambil data jadwal berdasarkan $id
        $schedule = Jadwal::find($id);

        // Mengupdate nilai kolom-kolom jadwal berdasarkan data yang diterima dari formulir
        $schedule->days_id = $request->input('days_id');
        $schedule->times_id = $request->input('times_id');
        $schedule->rooms_id = $request->input('rooms_id');
        // Tambahkan pembaruan lain sesuai kebutuhan Anda

        // Menyimpan perubahan ke dalam database
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Sementara Update SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method



    public function destroy($id)
    {
        $schedule = Jadwal::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Jadwal Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function updateStatus()
    {

        Jadwal::query()->update(['status' => 1]);

        $notification = array(
            'message' => 'Jadwal Berhasil Di Kirim SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function statusOne($id)
    {
        $schedule = Jadwal::find($id);
        $schedule->status = '1';
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Berhasil Di Kirim SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function allKepsek(Request $request)
    {
        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchLecturers = $request->input('searchlecturers');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

        // Filter berdasarkan nama hari jika searchlecturers tidak kosong
        if (!empty($searchDays)) {
            $query->whereHas('day', function ($lecturerQuery) use ($searchDays) {
                $lecturerQuery->where('name_day', 'LIKE', '%' . $searchDays . '%');
            });
        }

        // Filter berdasarkan nama guru jika searchlecturers tidak kosong
        if (!empty($searchLecturers)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchLecturers) {
                $teachQuery->whereHas('lecturer', function ($courseQuery) use ($searchLecturers) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchLecturers . '%');
                });
            });
        }

        // Filter berdasarkan nama mata Pelajaran jika searchcourse tidak kosong
        if (!empty($searchCourse)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchCourse) {
                $teachQuery->whereHas('course', function ($courseQuery) use ($searchCourse) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchCourse . '%');
                });
            });
        }
        // Filter berdasarkan nama kelas jika searchclass tidak kosong
        if (!empty($searchClass)) {
            $query->whereHas('teach', function ($lecturerQuery) use ($searchClass) {
                $lecturerQuery->where('class_room', 'LIKE', '%' . $searchClass . '%');
            });
        }
        // End Bagian search Data //

        // Mengambil data jadwal berdasarkan 'status'
        $schedules = $query->whereIn('status', [1, 2])
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();

        $day = Day::all();
        $time = Time::all();
        $room = Room::all();

        return view('admin.jadwal.jadwal_kepsek', compact('day', 'time', 'room', 'schedules'));
    }



    public function updateVerifikasi()
    {

        Jadwal::query()->update(['status' => 2]);

        $notification = array(
            'message' => 'Jadwal Berhasil Di Verifikasi SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method


    public function verifikasiOne($id)
    {
        $schedule = Jadwal::find($id);
        $schedule->status = '2';
        $schedule->save();
        $notification = array(
            'message' => 'Jadwal Berhasil Di Verifikasi SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } // end method




    public function allGuru(Request $request)
    {

        // Bagian search Data //
        $searchDays = $request->input('searchdays');
        $searchLecturers = $request->input('searchlecturers');
        $searchCourse = $request->input('searchcourse');
        $searchClass = $request->input('searchclass');

        // Query dasar yang akan digunakan untuk mencari data Teach
        $query = Jadwal::query();

        // Filter berdasarkan nama hari jika searchlecturers tidak kosong
        if (!empty($searchDays)) {
            $query->whereHas('day', function ($lecturerQuery) use ($searchDays) {
                $lecturerQuery->where('name_day', 'LIKE', '%' . $searchDays . '%');
            });
        }

        // Filter berdasarkan nama mata Pelajaran jika searchcourse tidak kosong
        if (!empty($searchCourse)) {
            $query->whereHas('teach', function ($teachQuery) use ($searchCourse) {
                $teachQuery->whereHas('course', function ($courseQuery) use ($searchCourse) {
                    $courseQuery->where('name', 'LIKE', '%' . $searchCourse . '%');
                });
            });
        }

        // Filter berdasarkan nama kelas jika searchclass tidak kosong
        if (!empty($searchClass)) {
            $query->whereHas('teach', function ($lecturerQuery) use ($searchClass) {
                $lecturerQuery->where('class_room', 'LIKE', '%' . $searchClass . '%');
            });
        }
        // End Bagian search Data //
        $userId = Auth::user()->id;

        $schedules = $query
            ->join(
                'teachs',
                'jadwals.teachs_id',
                '=',
                'teachs.id'
            )
            ->join('lecturers', 'teachs.lecturers_id', '=', 'lecturers.id')
            ->where('status', '2')
            ->where('lecturers.akun', '=', $userId)
            ->orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->get();
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();
        return view('admin.jadwal.jadwal_guru', compact('day', 'time', 'room', 'schedules'));
    } // end method


}
