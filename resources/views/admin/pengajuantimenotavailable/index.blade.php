@extends('admin.layouts.master')

@section('content')
    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="page-content">
        <h1 class="text-lg font-medium mb-4">Pengajuan Waktu Berhalangan</h1>
        <div class="mb-3 intro-y flex flex-col sm:flex-row items-center mt-4">
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                @if (Auth::user()->role == '5')
                    <a href="{{ route('admin.pengajuantimenotavailable.create') }}"
                        class="btn btn-primary shadow-md mr-2">Tambah
                        Data</a>
                @endif
            </div>
            <form role="form" action="{{ route('admin.pengajuantimenotavailables') }}" method="get" class="sm:flex">
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchlecturers" class="form-control"
                            placeholder="Mencari Berdasarkan Nama Dosen" value="{{ request('searchlecturers') }}">
                    </div>
                </div>
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchday" class="form-control" placeholder="Mencari Berdasarkan Hari"
                            value="{{ request('searchday') }}">
                    </div>
                </div>

                <div class="sm:ml-1">
                    <button type="submit" class="btn btn-default">Search</button>
                </div>
            </form>
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
                                        <th class="whitespace-nowrap">No.</th>
                                        <th class="whitespace-nowrap">Dosen</th>
                                        <th class="whitespace-nowrap">Hari</th>
                                        <th class="whitespace-nowrap">Waktu</th>
                                        <th class="whitespace-nowrap">Status</th>
                                        <th class="whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($timenotavailables as $key => $timenotavailable)
                                        <tr>
                                            <td align="center">
                                                {{ $key + 1 }}
                                            </td>
                                            <td>{{ $timenotavailable->lecturer->name ?? '' }}</td>
                                            <td>{{ $timenotavailable->day->name_day ?? '' }}</td>
                                            <td>{{ $timenotavailable->time->range ?? '' }}</td>
                                            <td>

                                                @if ($timenotavailable->status == '1')
                                                    <span class="btn btn-outline-warning">Di Proses</span>
                                                @elseif($timenotavailable->status == '2')
                                                    <span class="btn btn-outline-success">Di Terima</span>
                                                @elseif($timenotavailable->status == '3')
                                                    <span class="btn btn-outline-danger">Di Tolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->role == '5')
                                                    @if ($timenotavailable->status == '1')
                                                        <a href="{{ route('admin.pengajuantimenotavailable.edit', $timenotavailable->id) }}"
                                                            class="btn btn-success mr-1 mb-2">
                                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                                        </a>
                                                    @endif
                                                    <a id="delete"
                                                        href="{{ route('admin.pengajuantimenotavailable.delete', $timenotavailable->id) }}"
                                                        class="btn btn-danger mr-1 mb-2">
                                                        <i data-lucide="trash" class="w-4 h-4"></i>
                                                    </a>
                                                @endif

                                                @if (Auth::user()->role == '4')
                                                    @if ($timenotavailable->status == '1')
                                                        <a href="{{ route('admin.pengajuantimenotavailable.ditolak', $timenotavailable->id) }}"
                                                            class="btn btn-danger mr-1 mb-2" title="Tolak">
                                                            <i data-lucide="x-circle" class="w-4 h-4"></i> </a>
                                                        <a href="{{ route('admin.pengajuantimenotavailable.diterima', $timenotavailable->id) }}"
                                                            class="btn btn-success mr-1 mb-2" title="Terima">
                                                            <i data-lucide="check-circle" class="w-4 h-4"></i> </a>
                                                    @elseif($timenotavailable->status == '3')
                                                        <a href="{{ route('admin.pengajuantimenotavailable.diterima', $timenotavailable->id) }}"
                                                            class="btn btn-success mr-1 mb-2" title="Terima">
                                                            <i data-lucide="check-circle" class="w-4 h-4"></i> </a>
                                                    @elseif($timenotavailable->status == '2')
                                                        <a href="{{ route('admin.pengajuantimenotavailable.ditolak', $timenotavailable->id) }}"
                                                            class="btn btn-danger mr-1 mb-2" title="Tolak">
                                                            <i data-lucide="x-circle" class="w-4 h-4"></i> </a>
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@stop
