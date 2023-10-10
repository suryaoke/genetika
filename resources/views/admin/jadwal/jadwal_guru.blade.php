@extends('admin.layouts.master')

@section('title', 'Hasil Generate Algoritma')

@section('content')

    {{--  // Bagian search //  --}}
    <h1 class="text-lg font-medium mb-4 mt-4">Jadwal Mata Pelajaran </h1>

    <div class="mb-4 intro-y flex flex-col sm:flex-row items-center mt-4">

        <form role="form" action="{{ route('jadwal.all.guru') }}" method="get" class="sm:flex">
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchdays" class="form-control" placeholder="Hari"
                        value="{{ request('searchdays') }}">
                </div>
            </div>
           
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchcourse" class="form-control" placeholder="Mata Pelajaran"
                        value="{{ request('searchcourse') }}">
                </div>
            </div>
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchclass" class="form-control" placeholder="Kelas"
                        value="{{ request('searchclass') }}">
                </div>
            </div>
            <div class="sm:ml-1">
                <button type="submit" class="btn btn-default">Search</button>
            </div>
        </form>
    </div>
    {{--  // End Bagian search //  --}}



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

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


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
