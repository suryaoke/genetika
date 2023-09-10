<?php

namespace App\Http\Controllers\admin;

use App\Models\Day;
use App\Models\Lecturer;
use App\Models\Time;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTimenotavailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanTimenotavailableController extends Controller
{
    public function index(Request $request)
    {
        $searchlecturers   = $request->input('searchlecturers');
        $searchday         = $request->input('searchday');
        $timenotavailables = PengajuanTimenotavailable::whereHas('lecturer', function ($query) use ($searchlecturers) {

            if (!empty($searchlecturers)) {
                $query = $query->where('lecturers.name', 'LIKE', '%' . $searchlecturers . '%');
            }
        })->whereHas('day', function ($query) use ($searchday) {
            if (!empty($searchday)) {
                $query = $query->where('days.name_day', 'LIKE', '%' . $searchday . '%');
            }
        });

        $timenotavailables = $timenotavailables->orderBy('id', 'desc')->get();

        return view('admin.pengajuantimenotavailable.index', compact('timenotavailables'));
    }

    public function create(Request $request)
    {

        $lecturers = Lecturer::orderBy('name', 'asc')->pluck('name', 'id');
        $days      = Day::orderBy('name_day', 'asc')->pluck('name_day', 'id');
        $times     = Time::orderBy('range', 'asc')->pluck('range', 'id');

        return view('admin.pengajuantimenotavailable.create', compact('lecturers', 'days', 'times'));
    }


    public function store(Request $request)
    {
        // Validasi data yang masuk dari formulir
        $validator = Validator::make($request->all(), [
            'lecturers_id' => 'required',
            'days'      => 'required',
            'times'     => 'required',
            'status'    => 'required',
        ]);

        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil, simpan data ke dalam database
        $timenotavailable = new PengajuanTimenotavailable();
        $timenotavailable->lecturers_id = $request->input('lecturers_id');
        $timenotavailable->days_id = $request->input('days');
        $timenotavailable->times_id = $request->input('times');
        $timenotavailable->status = $request->input('status');
        $timenotavailable->save();

        $notification = array(
            'message' => 'Waktu Berhalangan Create SuccessFully',
            'alert-type' => 'success'
        );

        // Redirect ke halaman lain atau tampilkan pesan sukses
        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }



    public function edit($id)
    {
        $timenotavailables = PengajuanTimenotavailable::find($id);
        $lecturers         = Lecturer::orderBy('name', 'asc')->pluck('name', 'id');
        $days              = Day::orderBy('name_day', 'asc')->pluck('name_day', 'id');
        $times             = Time::orderBy('range', 'asc')->pluck('range', 'id');

        if ($timenotavailables == null) {
            return view('admin.layouts.404');
        }

        return view('admin.pengajuantimenotavailable.edit', compact('timenotavailables', 'lecturers', 'days', 'times'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lecturers' => 'required',
            'days'      => 'required',
            'times'     => 'required',

        ]);

        $timenotavailables               = PengajuanTimenotavailable::find($id);
        $timenotavailables->lecturers_id = $request->input('lecturers');
        $timenotavailables->days_id      = $request->input('days');
        $timenotavailables->times_id     = $request->input('times');
        $timenotavailables->save();

        $notification = array(
            'message' => 'Waktu Berhalangan Update SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }

    public function destroy($id)
    {
        PengajuanTimenotavailable::find($id)->delete();
        $notification = array(
            'message' => 'Pengajuan Delete SuccessFully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.pengajuantimenotavailables')->with($notification);
    }



    public function Ditolak($id)
    {

        // $users = User::findOrFail($id);
        // $img = $users->profile_image;
        // unlink($img);

        $user = PengajuanTimenotavailable::findOrFail($id);
        $user->status = '3';
        $user->save();

        $notification = array(
            'message' => 'Pengajuan Ditolak Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method

    public function Diterima($id)
    {

        // $users = User::findOrFail($id);
        // $img = $users->profile_image;
        // unlink($img);

        $user = PengajuanTimenotavailable::findOrFail($id);
        $user->status = '2';
        $user->save();

        $notification = array(
            'message' => 'Pengajuan Diterima Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method



}
