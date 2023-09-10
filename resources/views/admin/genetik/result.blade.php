@extends('admin.layouts.master')

@section('title', 'Hasil Generate Algoritma')

@section('style')
    <style type="text/css">
        .panel-body {
            width: auto;
            height: auto;
            overflow: auto;
        }
    </style>
@stop

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }} mb-4">
            {{ session('message') }}
        </div>
    @endif
    @if (isset($value_schedule))
        <div class="alert alert-success alert-dismissable">

            <h4>Nilai Fitness : 1 / 1 + (0{{ isset($value_schedule->value_process) ? $value_schedule->value_process : 0 }})
                = {{ isset($value_schedule->value) ? $value_schedule->value : 0 }}</h4>
            <h4>Crossover : {{ $crossover->value }}</h4>
            <h4>Mutasi : {{ $mutasi->value }}</h4>
        </div>
    @endif
    <div class="row">
        <div class="grid grid-cols-12 gap-2 mt-4 mb-4">
            <div class="mr-2"> <!-- Tombol Kembali -->
                <a class="btn btn-warning btn-block" href="{{ route('admin.generates', request()->all()) }}">
                    <span class="glyphicon glyphicon-hand-left"></span> <i data-lucide="skip-back" class="w-4 h-4"></i>
                    Back
                </a>
            </div>
            <div class="col-span-2"> <!-- Tombol Export Excel -->
                <a class="btn btn-primary btn-block" href="{{ route('admin.generates.excel', $id) }}">
                    <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="printer"
                        class="w-4 h-4"></i>&nbsp;Export Excel
                </a>
            </div>
            <div class="col-span-4 "> <!-- Dropdown -->
                @if (!empty($data_kromosom))
                    <select class="form-control select2" id="myAction">
                        @foreach ($data_kromosom as $key => $kromosom)
                            <option value="{{ $key + 1 }}"
                                @if ($id == $key + 1) selected="selected" @endif>
                                @if ($kromosom['value'] == 1)
                                    Jadwal Terbaik Ke {{ $key + 1 }}
                                @else
                                    Jadwal ke {{ $key + 1 }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-span-2  "> <!-- Tombol Export Excel -->
                <a class="btn btn-success btn-block" data-tw-toggle="modal" data-tw-target="#button-modal-preview">
                    <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="save"
                        class="w-4 h-4"></i>&nbsp;Simpan Data
                </a>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="overflow-x-auto">
                        <table id="datatable" class="table table-sm"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">No.</th>
                                    <th style="text-align:center;">Hari</th>
                                    <th style="text-align:center;">Jam</th>
                                    <th style="text-align:center;">Nama Ruangan</th>
                                    <th style="text-align:center;">Kapasitas Ruangan</th>
                                    <th style="text-align:center;">Mata Pelajaran</th>
                                    <th style="text-align:center;">Guru</th>
                                    <th style="text-align:center;">Semester</th>
                                    <th style="text-align:center;">JP</th>
                                    <th style="text-align:center;">Kelas</th>
                                    <th style="text-align:center;">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td align="center">{{ $key + 1 }}</td>
                                        <td>{{ isset($schedule->day->name_day) ? $schedule->day->name_day : '' }}</td>
                                        <td>{{ isset($schedule->time->range) ? $schedule->time->range : '' }}</td>
                                        <td>
                                            {{ isset($schedule->room->name) ? $schedule->room->name : '' }} :
                                            {{ isset($schedule->teach->class_room) ? $schedule->teach->class_room : '' }}
                                        </td>
                                        <td>{{ isset($schedule->room->capacity) ? $schedule->room->capacity : '' }}</td>
                                        <td>{{ isset($schedule->teach->course->name) ? $schedule->teach->course->name : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->lecturer->name) ? $schedule->teach->lecturer->name : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->course->semester) ? $schedule->teach->course->semester : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->course->jp) ? $schedule->teach->course->jp : '' }}
                                        </td>
                                        <td>{{ isset($schedule->teach->class_room) ? $schedule->teach->class_room : '' }}
                                        </td>
                                        <td>
                                            <a id="delete" href="{{ route('jadwal.delete', $schedule->id) }}"
                                                class="btn btn-danger mr-1 mb-2">
                                                <i data-lucide="trash" class="w-4 h-4"></i>
                                            </a>
                                            <a href="javascript:;" data-tw-toggle="modal"
                                                data-tw-target="#edit-schedule-modal-{{ $schedule->id }}"
                                                class="btn btn-primary">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jadwal -->
    @foreach ($schedules as $schedule)
        <div class="modal fade" id="edit-schedule-modal-{{ $schedule->id }}" tabindex="-1" role="dialog"
            aria-labelledby="edit-schedule-modal-label-{{ $schedule->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-schedule-modal-label-{{ $schedule->id }}">
                            Edit Jadwal
                            {{ $schedule->teach->lecturer->name }}
                        </h5>

                    </div>
                    <div class="modal-body">
                        <!-- Isi form edit jadwal di sini -->

                        <form method="post" action="{{ route('update.jadwal', $schedule->id) }}">
                            @csrf
                            <!-- Field dan input form untuk mengedit data jadwal -->
                            <div class="form-group">
                                <label for="edit-hari">Hari</label>
                                <select name="days_id" id="edit-hari" class="form-control w-full" required>
                                    <option value="{{ $schedule->day->id }}">{{ $schedule->day->name_day }}</option>
                                    @foreach ($day as $days)
                                        <option value="{{ $days->id }}">{{ $days->name_day }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="edit-jam">Jam</label>
                                <select name="times_id" id="edit-jam" class="form-control w-full" required>
                                    <option value="{{ $schedule->time->id }}">{{ $schedule->time->range }}</option>
                                    @foreach ($time as $times)
                                        <option value="{{ $times->id }}">{{ $times->range }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="edit-ruangan">Ruangan</label>
                                <select name="rooms_id" id="edit-ruangan" class="form-control w-full" required>
                                    <option value="{{ $schedule->room->id }}"> {{ $schedule->room->name }}
                                    </option>
                                    @foreach ($room as $rooms)
                                        <option value="{{ $rooms->id }}">{{ $rooms->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-4"> <button type="button" data-tw-dismiss="modal"
                                    class="btn btn-outline-secondary w-20 mr-1">Cancel
                                </button>
                                <button type="submit" class="btn btn-primary w-20">Save</button>
                            </div>
                            <!-- END: Modal Footer -->
                        </form>

                        <!-- Akhir form edit jadwal -->
                    </div>
                </div>
            </div>
        </div>
    @endforeach






    <!-- BEGIN: Modal Simpan Jadwal -->

    <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('jadwal.save', ['id' => $id]) }}">
                @csrf
                <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i> </a>
                    <div class="modal-body p-0">
                        <div class="p-5 text-center"> <i data-lucide="check-circle"
                                class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Simpan Jadwal </div>
                            <div class="text-slate-500 mt-2">Data yang telah disimpan sebelumnya akan terhapus..!!</div>
                        </div>
                        <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                class="btn btn-primary w-24" >Ok</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END: Modal Content -->









    <!-- Masukkan jQuery sebelum kode JavaScript Anda -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Kode JavaScript Anda -->
    <script type="text/javascript">
        $('#myAction').change(function() {
            var action = $(this).val();
            window.location = action;
        });
    </script>



@stop
