@extends('layout.main')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Barang</h3>
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
                            <h3 class="card-title">Edit Barang</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('barang.update', $barang->IDBarang) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Nama Barang
                                        </label>
                                        <input value="{{ $barang->NamaBarang }}" type="text" class="form-control"
                                            name="nama_barang">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Harga Satuan
                                        </label>
                                        <input value="{{ $barang->HargaSatuan }}" type="number" class="form-control"
                                            name="harga_satuan">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Jenis Barang
                                    </label>
                                    <select class="form-select" id="validationCustom04" name="jenis_barang" required>
                                        <option selected disabled value="">Choose...</option>
                                        @foreach ($jenis_barangs as $item)
                                            <option {{ $barang->IDJenisBarang == $item->IDJenisBarang ? 'selected' : '' }}
                                                value="{{ $item->IDJenisBarang }}">{{ $item->JenisBarang }}</option>
                                        @endforeach
                                    </select>
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
