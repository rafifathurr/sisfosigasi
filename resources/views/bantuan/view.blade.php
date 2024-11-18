@extends('layout.main')
@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Bantuan</h3>
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
                            <h3 class="card-title">view Bantuan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <table>
                                        <tr>
                                            <th style="padding-top: 10px; padding-bottom: 10px;" class="text-left"
                                                scope="row">
                                                Nama Perusahaan
                                            </th>
                                            <td width="10%">
                                                <center>:</center>
                                            </td>
                                            <td>
                                                {{ $bantuan->donatur->NamaPerusahaan }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding-top: 10px; padding-bottom: 10px;" class="text-left"
                                                scope="row">
                                                Alamat Perusahaan
                                            </th>
                                            <td width="10%">
                                                <center>:</center>
                                            </td>
                                            <td>
                                                {{ $bantuan->donatur->Alamat }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding-top: 10px; padding-bottom: 10px;" class="text-left"
                                                scope="row">
                                                Nomor Perusahaan
                                            </th>
                                            <td width="10%">
                                                <center>:</center>
                                            </td>
                                            <td>
                                                {{ $bantuan->donatur->NomorKontak }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table>
                                        <tr>
                                            <th style="padding-top: 10px; padding-bottom: 10px;" class="text-left"
                                                scope="row">
                                                Tanggal Bantuan
                                            </th>
                                            <td width="10%">
                                                <center>:</center>
                                            </td>
                                            <td>
                                                {{ $bantuan->TanggalBantuan }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <h5 class="mt-4">List Barang</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bantuan->bantuan_detail as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->barang->NamaBarang }}
                                            </td>
                                            <td>
                                                {{ $item->Jumlah }}
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
    </div>
@endsection
