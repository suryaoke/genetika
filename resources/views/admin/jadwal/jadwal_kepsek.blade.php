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
    {{--  // Bagian search //  --}}
    <h1 class="text-lg font-medium mb-4 mt-4">Jadwal Mata Pelajaran All</h1>

    <div class="mb-4 intro-y flex flex-col sm:flex-row items-center mt-4">

        <form role="form" action="{{ route('jadwal.all.kepsek') }}" method="get" class="sm:flex">
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchdays" class="form-control" placeholder="Hari"
                        value="{{ request('searchdays') }}">
                </div>
            </div>
            <div class="flex-1 sm:mr-2">
                <div class="form-group">
                    <input type="text" name="searchlecturers" class="form-control" placeholder="Nama Guru"
                        value="{{ request('searchlecturers') }}">
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

    <div class="col-span-2 mb-4 ">
        <a class="btn btn-primary btn-block" data-tw-toggle="modal" data-tw-target="#button-modal-preview">
            <span class="glyphicon glyphicon-download"></span> </span> <i data-lucide="check-circle"
                class="w-4 h-4"></i>&nbsp;Verifikasi
            Jadwal All
        </a>
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
                                    <th style="text-align:center;">Status</th>
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
                                            @if ($schedule->status == '0')
                                                <span class="btn btn-outline-warning">Proses Penjadwalan</span>
                                            @elseif($schedule->status == '1')
                                                <span class="btn btn-outline-pending">Menunggu Verifikasi</span>
                                            @elseif($schedule->status == '2')
                                                <span class="btn btn-outline-success">Kirim</span>
                                             @elseif($schedule->status == '3')
                                                <span class="btn btn-outline-danger">Ditolak</span>
                                            @endif
                                       

                                        </td>

                                        <td>
                                            @if ($schedule->status == '1')
                                                <a href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#kirim-schedule-modal-{{ $schedule->id }}"
                                                    class="btn btn-primary">
                                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                </a>
                                                <a href="javascript:;" data-tw-toggle="modal"
                                                    data-tw-target="#tolak-schedule-modal-{{ $schedule->id }}"
                                                    class="btn btn-danger mt-2">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            @endif

                                           
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




    <!-- BEGIN: Modal Verifikasi Jadwal All-->
    <div id="button-modal-preview" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('jadwal.verifikasi') }}">
                @csrf
                <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                            class="w-8 h-8 text-slate-400"></i> </a>
                    <div class="modal-body p-0">
                        <div class="p-5 text-center"> <i data-lucide="check-circle"
                                class="w-16 h-16 text-success mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Verifikasi Jadwal </div>
                            <div class="text-slate-500 mt-2">Jadwal Mata Pelajaran Di Verifikasi Dan <br> Di kirim
                                Ke Guru..!!</div>
                        </div>
                        <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                class="btn btn-primary w-24">Ok</button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- END: Modal Content -->


    <!-- BEGIN: Modal Verifikasi Jadwal Satu-->
    @foreach ($schedules as $schedule)
        <div id="kirim-schedule-modal-{{ $schedule->id }}" class="modal" tabindex="-1" aria-hidden="true"
            aria-labelledby="kirim-schedule-modal-label-{{ $schedule->id }}">
            <div class="modal-dialog">

                <form method="post" action="{{ route('jadwal.verifikasi.one', $schedule->id) }}">
                    @csrf
                    <input type="hidden" value="2" name="status">
                    <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                                class="w-8 h-8 text-slate-400"></i> </a>
                        <div class="modal-body p-0">
                            <div class="p-5 text-center"> <i data-lucide="check-circle"
                                    class="w-16 h-16 text-success mx-auto mt-3"></i>
                                <div class="text-3xl mt-5">Verifikasi Jadwal </div>
                                <div class="text-slate-500 mt-2">Jadwal Mata Pelajaran Di Verifikasi Dan <br> Di kirim
                                    Ke Guru..!!</div>
                            </div>
                            <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                                    class="btn btn-primary w-24">Ok</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- END: Modal Content -->
    @endforeach



  <!-- BEGIN: Modal Tolak Jadwal Satu-->
  @foreach ($schedules as $schedule)
  <div id="tolak-schedule-modal-{{ $schedule->id }}" class="modal" tabindex="-1" aria-hidden="true"
      aria-labelledby="tolak-schedule-modal-label-{{ $schedule->id }}">
      <div class="modal-dialog">

          <form method="post" action="{{ route('jadwal.verifikasi.tolak', $schedule->id) }}">
              @csrf
              <input type="hidden" value="1" name="status">
              <div class="modal-content"> <a data-tw-dismiss="modal" href="javascript:;"> <i data-lucide="x"
                          class="w-8 h-8 text-slate-400"></i> </a>
                  <div class="modal-body p-0">
                      <div class="p-5 text-center"> <i data-lucide="x"
                              class="w-16 h-16 text-danger mx-auto mt-3"></i>
                          <div class="text-3xl mt-5">Tolak Jadwal </div>
                          <div class="text-slate-500 mt-2">Data Jadwal Mata Pelajaran Di Kirim Ke Wakil..!!
                          </div>
                      </div>
                      <div class="px-5 pb-8 text-center"> <button type="submit" data-tw-dismiss="modal"
                              class="btn btn-primary w-24">Ok</button>

                      </div>
                  </div>
              </div>
          </form>
      </div>
  </div> <!-- END: Modal Content -->
@endforeach

<!-- BEGIN: Modal tolak Jadwal Satu-->



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
