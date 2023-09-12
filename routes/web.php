<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\DayController;
use App\Http\Controllers\Admin\GenetikController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\LecturersController;
use App\Http\Controllers\admin\PengajuanTimenotavailableController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\TeachController;
use App\Http\Controllers\Admin\TimeController;
use App\Http\Controllers\Admin\TimenotavailableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'], function () {
    // Route Login
    Route::get('/', ['as' => 'admin.login', 'uses' => 'AuthController@index']);
    Route::post('/submit', ['as' => 'admin.login.submit', 'uses' => 'AuthController@login']);
    Route::group(['middleware' => ['auth.admin'], 'prefix' => 'admin'], function () {
        Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'SiteController@index']);
        // AJAX
        Route::get('ajax/user/email', ['as' => 'ajax.user.email', 'uses' => 'AjaxController@EmailUser']);
        Route::get('ajax/lecturer/email', ['as' => 'ajax.lecturer.email', 'uses' => 'AjaxController@EmailLecturer']);
        Route::get('ajax/lecturer/nidn', ['as' => 'ajax.lecturer.nidn', 'uses' => 'AjaxController@NidnLecturer']);
        Route::get('ajax/course/name', ['as' => 'ajax.course.name', 'uses' => 'AjaxController@NameCourses']);
        Route::get('ajax/course/code', ['as' => 'ajax.course.code', 'uses' => 'AjaxController@CodeCourses']);
        Route::get('ajax/room/name', ['as' => 'ajax.room.name', 'uses' => 'AjaxController@NameRooms']);
        Route::get('ajax/room/code', ['as' => 'ajax.room.code', 'uses' => 'AjaxController@CodeRooms']);
        Route::get('ajax/teach/courses', ['as' => 'ajax.teach.courses', 'uses' => 'AjaxController@Teachsroom']);
    });
});



//Route jadwal 
Route::controller(JadwalController::class)->group(function () {
    Route::get('jadwal', 'all')->name('jadwal.all');
    Route::post('jadwal/update/{id}', 'updatejadwal')->name('jadwal.update');
    Route::get('jadwal/delete/{id}', 'destroy')->name('jadwal.delete');
    Route::post('jadwal/upadate/status', 'updateStatus')->name('jadwal.status');
    Route::post('jadwal/upadate/status/one/{id}', 'statusOne')->name('jadwal.status.one');
    Route::get('jadwal/kepsek', 'allKepsek')->name('jadwal.all.kepsek');
    Route::post('jadwal/upadate/verifikasi', 'updateVerifikasi')->name('jadwal.verifikasi');
    Route::post('jadwal/upadate/verifikasi/one/{id}', 'verifikasiOne')->name('jadwal.verifikasi.one');
});


// Route generate jadwal 
Route::controller(GenetikController::class)->group(function () {
    Route::get('generates', 'index')->name('admin.generates');
    Route::get('generates/submit', 'submit')->name('admin.generates.submit');
    Route::get('generates/result/{id}', 'result')->name('admin.generates.result');
    Route::get('generates/excel/{id}', 'excel')->name('admin.generates.excel');
    Route::post('generates/update/{id?}', 'updatejadwal')->name('generate.update');
    Route::get('generates/delete/{id?}', 'destroy')->name('generate.delete');
    Route::post('generates/save/{id?}', 'saveDataToMapel')->name('generate.save');
});


// Route Guru
Route::controller(LecturersController::class)->group(function () {
    Route::get('lecturers/delete/{id}', 'destroy')->name('admin.lecturer.delete');
    Route::get('lecturers', 'index')->name('admin.lecturers');
    Route::get('lecturers/create', '@create')->name('admin.lecturer.create');
    Route::post('lecturers/create', 'store')->name('admin.lecturer.store');
    Route::get('lecturers/edit/{id}', 'edit')->name('admin.lecturer.edit');
    Route::post('lecturers/update/{id?}', 'update')->name('admin.lecturer.update');
});

// Route Mata Pelajaran
Route::controller(CoursesController::class)->group(function () {
    Route::get('courses/delete/{id}', 'destroy')->name('admin.courses.delete');
    Route::get('courses', 'index')->name('admin.courses');
    Route::get('courses/create', 'create')->name('admin.courses.create');
    Route::post('courses/create', 'store')->name('admin.courses.store');
    Route::get('courses/edit/{id}', 'edit')->name('admin.courses.edit');
    Route::post('courses/update/{id?}', 'update')->name('admin.courses.update');
});

// Route Waktu
Route::controller(TimeController::class)->group(function () {
    Route::get('times/delete/{id}', 'destroy')->name('admin.time.delete');
    Route::get('times', 'index')->name('admin.times');
    Route::get('times/create', 'create')->name('admin.time.create');
    Route::post('times/create', 'store')->name('admin.time.store');
    Route::get('times/edit/{id}', 'edit')->name('admin.time.edit');
    Route::post('times/update/{id?}', 'update')->name('admin.time.update');
});

// Route Hari 
Route::controller(DayController::class)->group(function () {
    Route::get('days/delete/{id}', 'destroy')->name('admin.day.delete');
    Route::get('days', 'index')->name('admin.days');
    Route::get('days/create', 'create')->name('admin.day.create');
    Route::post('days/create', 'store')->name('admin.day.store');
    Route::get('days/edit/{id}', 'edit')->name('admin.day.edit');
    Route::post('days/update/{id?}', 'update')->name('admin.day.update');
});

// Route  Waktu Berhalangan
Route::controller(TimenotavailableController::class)->group(function () {
    Route::get('timenotavailable/delete/{id}', 'destroy')->name('admin.timenotavailable.delete');
    Route::get('timenotavailable', 'index')->name('admin.timenotavailables');
    Route::get('timenotavailable/create', 'create')->name('admin.timenotavailable.create');
    Route::post('timenotavailable/create', 'store')->name('admin.timenotavailable.store');
    Route::get('timenotavailable/edit/{id}', 'edit')->name('admin.timenotavailable.edit');
    Route::post('timenotavailable/update/{id?}', 'update')->name('admin.timenotavailable.updat');
});

// Route Pengajuan Waktu Berhalangan
Route::controller(PengajuanTimenotavailableController::class)->group(function () {
    Route::get('/pengajuantimenotavailable/delete/{id}', 'destroy')->name('admin.pengajuantimenotavailable.delete');
    Route::get('/pengajuantimenotavailable/ditolak/{id}', 'Diterima')->name('admin.pengajuantimenotavailable.diterima');
    Route::get('/pengajuantimenotavailable/diterima/{id}', 'Ditolak')->name('admin.pengajuantimenotavailable.ditolak');
    Route::get('pengajuantimenotavailable', 'index')->name('admin.pengajuantimenotavailables');
    Route::get('pengajuantimenotavailable/create', 'create')->name('admin.pengajuantimenotavailable.create');
    Route::post('pengajuantimenotavailable/create', 'store')->name('admin.pengajuantimenotavailable.store');
    Route::get('pengajuantimenotavailable/edit/{id}', 'edit')->name('admin.pengajuantimenotavailable.edit');
    Route::post('pengajuantimenotavailable/update/{id?}', 'update')->name('admin.pengajuantimenotavailable.update');
});

// Route User
Route::controller(UserController::class)->middleware(['auth'])->group(function () {
    Route::get('/user/view/{id}', 'UserView')->name('user.view');
    Route::get('/user/edit/{id}', 'UserEdit')->name('user.edit');
    Route::post('/user/update', 'UserUpdate')->name('user.update');
    Route::get('users/delete/{id}', 'destroy')->name('admin.user.delete');
    Route::post('/user/reset', 'UserReset')->name('user.reset');
    Route::get('/user/tidak/aktif{id}', 'UserTidakAktif')->name('user.tidak.aktif');
    Route::get('/user/aktif{id}', 'UserAktif')->name('user.aktif');
    Route::get('users', 'index')->name('admin.user');
    Route::get('users/create', 'create')->name('admin.user.create');
    Route::post('users/create', 'store')->name('admin.user.store');
    Route::get('users/edit/{id}', 'edit')->name('admin.user.edit');
    Route::post('users/update/{id?}', 'update')->name('admin.user.update');
});

// Route Ruangan
Route::controller(RoomsController::class)->group(function () {
    Route::get('rooms/delete/{id}', 'destroy')->name('admin.room.delete');
    Route::get('rooms', 'index')->name('admin.rooms');
    Route::get('rooms/create', 'create')->name('admin.room.create');
    Route::post('rooms/create', 'store')->name('admin.room.store');
    Route::get('rooms/edit/{id}', 'edit')->name('admin.room.edit');
    Route::post('rooms/update/{id?}', 'update')->name('admin.room.update');
});

// Route Jurusan
Route::controller(JurusanController::class)->group(function () {
    Route::get('jurusan/delete/{id}', 'destroy')->name('admin.jurusan.delete');
    Route::get('jurusan', 'index')->name('admin.jurusans');
    Route::get('jurusan/create', 'create')->name('admin.jurusan.create');
    Route::post('jurusan/create', 'store')->name('admin.jurusan.store');
    Route::get('jurusan/edit/{id}', 'edit')->name('admin.jurusan.edit');
    Route::post('jurusan/update/{id?}', 'update')->name('admin.jurusan.update');
});

// Route Logout
Route::controller(AuthController::class)->middleware(['auth'])->group(function () {
    Route::get('logout', 'logout')->name('admin.logout');
});

// Route Admin
Route::controller(AdminController::class)->middleware(['auth'])->group(function () {
    Route::get('/admin/profile', 'Profile')->name('admin.profile');
    Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
    Route::post('/store/profile', 'StoreProfile')->name('store.profile');
    Route::get('/change/password', 'ChangePassword')->name('change.password');
    Route::post('/update/password', 'UpdatePassword')->name('update.password');
});

// Route Pengampu
Route::controller(TeachController::class)->middleware(['auth'])->group(function () {
    Route::get('teachs/delete/{id}', 'destroy')->name('admin.teach.delete');
    Route::get('teachs', 'index')->name('admin.teachs');
    Route::get('teachs/create', 'create')->name('admin.teach.create');
    Route::post('teachs/create', 'store')->name('admin.teach.store');
    Route::get('teachs/edit/{id}', 'edit')->name('admin.teach.edit');
    Route::post('teachs/update/{id?}', 'update')->name('admin.teach.update');
});

// Route Forgot Password //
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});
