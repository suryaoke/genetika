<?php

namespace App\Http\Controllers\Admin;

use App\Algoritma\GenerateAlgoritma;
use App\Exports\SchedulesExport;
use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Jadwal;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Teach;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GenetikController extends Controller
{
    public function index(Request $request)
    {



        $years = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        return view('admin.genetik.index', compact('years'));
    }

    public function submit(Request $request)
    {

        $years            = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        $input_kromosom   = $request->input('kromosom');
        $input_year       = $request->input('year');
        $input_semester   = $request->input('semester');
        $input_generasi   = $request->input('generasi');
        $input_crossover  = $request->input('crossover');
        $input_mutasi     = $request->input('mutasi');
        $count_lecturers  = Lecturer::count();
        $count_teachs     = Teach::count();
        $kromosom         = $input_kromosom * $input_generasi;
        $crossover        = $input_kromosom * $input_crossover;
        $generate         = new GenerateAlgoritma;
        $data_kromosoms   = $generate->randKromosom($kromosom, $count_teachs, $input_year, $input_semester);
        $result_schedules = $generate->checkPinalty();

        $total_gen        = Setting::firstOrNew(['key' => 'total_gen']);
        $total_gen->name  = 'Total Gen';
        $total_gen->value = $crossover;
        $total_gen->save();

        $mutasi        = Setting::firstOrNew(['key' => 'mutasi']);
        $mutasi->name  = 'Mutasi';
        $mutasi->value = (3 * $count_teachs) * $input_kromosom * $input_mutasi;
        $mutasi->save();

        return redirect()->route('admin.generates.result', 1);
    } // end method

    public function result(Request $request, $id)
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


        // Menampilkan Data  Generate Jadwal//
        $years          = Teach::select('year')->groupBy('year')->pluck('year', 'year');
        $kromosom       = Schedule::select('type')->groupBy('type')->get()->count();
        $crossover      = Setting::where('key', Setting::CROSSOVER)->first();
        $mutasi         = Setting::where('key', Setting::MUTASI)->first();
        $value_schedule = Schedule::where('type', $id)->first();
        $schedules = Schedule::orderBy('days_id', 'asc')
            ->orderBy('times_id', 'asc')
            ->where('schedules.type', $id);
        $schedules = $query->get();
        if (empty($value_schedule)) {
            abort(404);
        }

        for ($i = 1; $i <= $kromosom; $i++) {
            $value_schedules = Schedule::where('type', $i)->first();
            $data_kromosom[] = [
                'value' => $value_schedules->value,

            ];
        }
        $day = Day::all();
        $time = Time::all();
        $room = Room::all();

        return view('admin.genetik.result', compact('day', 'time', 'room', 'schedules', 'years', 'data_kromosom', 'id', 'value_schedule', 'crossover', 'mutasi'));
    }


    public function excel($id)
    {
        $schedules = Schedule::with('day', 'time', 'room', 'teach.course', 'teach.lecturer')
            ->orderBy('days_id', 'desc')
            ->orderBy('times_id', 'desc')
            ->where('type', $id)
            ->get();

        $export = new SchedulesExport($schedules);
        return Excel::download($export, 'AlgoritmaGenetika.xlsx');
    } // end method


    public function updatejadwal(Request $request, $id)
    {

        $this->validate($request, [
            'days_id' => 'required',
            'times_id' => 'required',
            'rooms_id' => 'required',
            // Tambahkan validasi lain sesuai kebutuhan Anda
        ]);

        // Mengambil data jadwal berdasarkan $id
        $schedule = Schedule::find($id);

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
        $schedule = Schedule::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Jadwal Delete SuccessFully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function saveDataToMapel($id)
    {
        // Ambil data schedule berdasarkan tipe (type) yang diberikan dalam parameter
        Jadwal::truncate();

        $schedules = Schedule::where('type', $id)->get();

        // Perulangan untuk menyimpan data ke tabel Mapel
        foreach ($schedules as $schedule) {
            // Buat instance baru dari model Mapel
            $mapel = new Jadwal();

            // Set atribut-atribut yang sesuai
            $mapel->teachs_id = $schedule->teachs_id;
            $mapel->days_id = $schedule->days_id;
            $mapel->times_id = $schedule->times_id;
            $mapel->rooms_id = $schedule->rooms_id;
            $mapel->status = '0';
            // Simpan data ke dalam tabel Mapel
            $mapel->save();
        }

        $notification = array(
            'message' => 'Jadwal Disimpan SuccessFully',
            'alert-type' => 'success'
        );
        // Redirect atau kirim pesan sukses jika diperlukan
        return redirect()->back()->with($notification);
    }
}
