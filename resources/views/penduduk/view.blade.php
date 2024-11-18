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
                            <h3 class="card-title">view Penduduk</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Nomor KTP
                                    </label>
                                    <input type="number" disabled value="{{ $penduduk->KTP }}" class="form-control"
                                        name="ktp">
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" disabled value="{{ $penduduk->Nama }}" class="form-control"
                                        name="nama">
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Jenis Kelamin
                                    </label>
                                    @if ($penduduk->JenisKelamin == 0)
                                        <input type="text" disabled value="Laki - Laki" class="form-control"
                                            name="nama">
                                    @else
                                        <input type="text" disabled value="Perempuan" class="form-control"
                                            name="nama">
                                    @endif
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Kelompok
                                    </label>
                                    <input type="text" disabled value="{{ $penduduk->kelompok->NamaKelompok }}"
                                        class="form-control" name="tanggal_lahir">
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Tanggal Lahir
                                    </label>
                                    <input type="date" disabled
                                        value="{{ \Carbon\Carbon::parse($penduduk->TanggalLahir)->format('Y-m-d') }}"
                                        class="form-control" name="tanggal_lahir">
                                </div>
                                <div class="mb-3 col-6">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Desa
                                    </label>
                                    <input type="text" disabled value="{{ $penduduk->Desa }}" class="form-control"
                                        name="desa">
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="exampleInputEmail1" class="form-label">
                                        Alamat Lengkap
                                    </label>
                                    <textarea disabled class="form-control" name="alamat" rows="3">{{ $penduduk->Alamat }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
