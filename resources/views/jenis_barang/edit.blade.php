@extends('layout.main')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Jenis Barang</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Fixed Layout
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Edit Jenis Barang</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('jenis-barang.update', $data->IDJenisBarang) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Nama Jenis Barang
                                    </label>
                                    <input value="{{ $data->JenisBarang }}" type="text" class="form-control"
                                        name="jenis_barang">
                                </div>
                                <button type="submit" class="btn btn-warning ms-auto">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
