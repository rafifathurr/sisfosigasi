@extends('layout.main')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
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
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Tambah Penduduk</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('penduduk.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Nomor KTP
                                        </label>
                                        <input type="number" class="form-control" name="ktp">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Nama Lengkap
                                        </label>
                                        <input type="text" class="form-control" name="nama">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Jenis Kelamin
                                        </label>
                                        <select class="form-select" id="validationCustom04" name="jenis_kelamin" required>
                                            <option selected disabled value="">Pilih Jenis Kelamin</option>
                                            <option value="0">Laki Laki</option>
                                            <option value="1">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Kelompok
                                        </label>
                                        <select class="form-select" id="validationCustom04" name="kelompok" required>
                                            <option selected disabled value="">Pilih Kelompok</option>
                                            @foreach ($kelompoks as $item)
                                                <option value="{{ $item->IDKelompok }}">{{ $item->NamaKelompok }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Tanggal Lahir
                                        </label>
                                        <input type="date" class="form-control" name="tanggal_lahir">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Desa
                                        </label>
                                        <input type="text" class="form-control" name="desa">
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Alamat Lengkap
                                        </label>
                                        <textarea class="form-control" name="alamat" rows="3"></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary ms-auto">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
