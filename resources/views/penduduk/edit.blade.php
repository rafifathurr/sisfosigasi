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
                            <h3 class="card-title">Edit Penduduk</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('penduduk.update', $penduduk->IDPenduduk) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="ktp" class="form-label">Nomor KTP</label>
                                        <input type="number" value="{{ $penduduk->KTP }}" class="form-control"
                                            name="ktp" id="ktp">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" value="{{ $penduduk->Nama }}" class="form-control"
                                            name="nama" id="nama">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                        <select class="form-select" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value="0" {{ $penduduk->JenisKelamin == 0 ? 'selected' : '' }}>Laki
                                                - Laki</option>
                                            <option value="1" {{ $penduduk->JenisKelamin == 1 ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="kelompok" class="form-label">Kelompok</label>
                                        <select class="form-select" name="kelompok" id="kelompok">
                                            @foreach ($kelompoks as $item)
                                                <option value="{{ $item->IDKelompok }}"
                                                    {{ $item->IDKelompok == $penduduk->kelompok->IDKelompok ? 'selected' : '' }}>
                                                    {{ $item->NamaKelompok }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date"
                                            value="{{ \Carbon\Carbon::parse($penduduk->TanggalLahir)->format('Y-m-d') }}"
                                            class="form-control" name="tanggal_lahir" id="tanggal_lahir">
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="desa" class="form-label">Desa</label>
                                        <input type="text" value="{{ $penduduk->Desa }}" class="form-control"
                                            name="desa" id="desa">
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                                        <textarea class="form-control" name="alamat" id="alamat" rows="3">{{ $penduduk->Alamat }}</textarea>
                                    </div>
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
