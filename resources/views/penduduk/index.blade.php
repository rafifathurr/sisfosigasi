@extends('layout.main')
@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Penduduk</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Fixed Layout
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-12"> <!-- Default box -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">List Penduduk</h3>
                            <a href="{{ route('penduduk.create') }}" class="btn btn-primary btn-sm ms-auto">Tambah</a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered datatables">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nomor KTP</th>
                                        <th>Nama</th>
                                        <th>Kelompok</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->KTP }}</td>
                                            <td>{{ $item->Nama }}</td>
                                            <td>{{ $item->kelompok->NamaKelompok ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group dropstart">
                                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('penduduk.show', $item->IDPenduduk) }}">
                                                                View
                                                            </a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('penduduk.edit', $item->IDPenduduk) }}">Edit</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#delete-{{ $item->IDPenduduk }}"
                                                                href="#">
                                                                Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <div class="modal fade" id="delete-{{ $item->IDPenduduk }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Warning</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apa anda yakin melakukan Delete ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tidak</button>
                                                            <form
                                                                action="{{ route('penduduk.destroy', $item->IDPenduduk) }}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="sumbit"
                                                                    class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content-->
    @push('javascript')
    @endpush
@endsection
