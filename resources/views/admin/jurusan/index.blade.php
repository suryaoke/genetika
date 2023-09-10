@extends('admin.layouts.master')

@section('content')

    @if (session('message'))
        <div class="alert alert-{{ session('alert-type') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="page-content">
        <h1 class="text-lg font-medium mb-4">Data Jurusan All</h1>
        <div class="mb-3 intro-y flex flex-col sm:flex-row items-center mt-4">
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Data</a>
            </div>
            <form role="form" action="{{ route('admin.jurusans') }}" method="get" class="sm:flex">
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchcode" class="form-control"
                            placeholder="Mencari Berdasarkan Kode Jurusan" value="{{ request('searchcode') }}">
                    </div>
                </div>
                <div class="flex-1 sm:mr-2">
                    <div class="form-group">
                        <input type="text" name="searchname" class="form-control"
                            placeholder="Mencari Berdasarkan Nama Jurusan" value="{{ request('searchname') }}">
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
                                        <th class="whitespace-nowrap">Nama Jurusan</th>
                                        <th class="whitespace-nowrap">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jurusans as $key => $jurusan)
                                        <tr>
                                            <td align="center">
                                                {{ $key + 1 }}
                                            </td>

                                            <td>
                                                {{ $jurusan->name }}
                                            </td>
                                            <td>
                                                <a id="delete" href="{{ route('admin.jurusan.delete', $jurusan->id) }}"
                                                    class="btn btn-danger mr-1 mb-2">
                                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                                </a>
                                                <a href="{{ route('admin.jurusan.edit', $jurusan->id) }}"
                                                    class="btn btn-success mr-1 mb-2">
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
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@stop
