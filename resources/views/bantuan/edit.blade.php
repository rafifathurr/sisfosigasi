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
                            <h3 class="card-title">Edit Bantuan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('bantuan.update', $bantuan->IDBantuan) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Donatur
                                        </label>
                                        <select class="form-select" id="validationCustom04" name="donatur" required>
                                            <option selected disabled value="">Pilih Donatur</option>
                                            @foreach ($donaturs as $item)
                                                <option {{ $bantuan->IDDonatur == $item->IDDonatur ? 'selected' : '' }}
                                                    value="{{ $item->IDDonatur }}">{{ $item->NamaPerusahaan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Barang
                                        </label>
                                        <div class="input-group">
                                            <select class="form-select" id="barang" name="jenis_barang">
                                                <option selected disabled value="">Pilih Barang</option>
                                                @foreach ($barangs as $item)
                                                    <option value="{{ $item->IDBarang }}">{{ $item->NamaBarang }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-text">
                                                <a id="addItemBtn" class="btn btn-sm">
                                                    <i class="bi bi-plus-square"></i>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-12">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Tanggal Bantuan
                                        </label>
                                        <input type="date"
                                            value="{{ \Carbon\Carbon::parse($bantuan->TanggalBantuan)->format('Y-m-d') }}"
                                            name="tanggal_bantuan" class="form-control">
                                    </div>
                                </div>
                                <h5 class="mt-4">List Barang</h5>
                                <table class="table">
                                    <thead>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($bantuan->bantuan_detail as $item)
                                            <tr class="barang-${barangId}">
                                                <td>
                                                    {{ $item->barang->NamaBarang }}
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        name="barang[{{ $item->IDBarang }}][jumlah_barang]"
                                                        value="{{ $item->Jumlah }}" min="1">
                                                    <input type="hidden" name="barang[{{ $item->IDBarang }}][id_barang]"
                                                        value="{{ $item->IDBarang }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-remove">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary ms-auto">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript')
        <script>
            $(document).ready(function() {
                $('#addItemBtn').on('click', function() {
                    const itemName = $('#barang option:selected').text();
                    const barangId = $('#barang option:selected').val();

                    console.log(itemName, barangId);


                    if (!itemName || itemName === 'Pilih Barang') {
                        alert("Silakan pilih barang!");
                        return;
                    }

                    // Check if item already exists
                    const existingRow = $(`.barang-${barangId}`);

                    if (existingRow.length > 0) {
                        // If exists, get current quantity and increment
                        const qtyInput = existingRow.find('input[name="qty[]"]');
                        const currentQty = parseInt(qtyInput.val()) || 0;
                        qtyInput.val(currentQty + 1);
                    } else {
                        // If not exists, add new row
                        $('#tableBody').append(`
                        <tr class="barang-${barangId}">
                            <td>
                                ${itemName}
                            </td>
                            <td>
                                <input type="number"
                                    class="form-control"
                                    name="barang[${barangId}][jumlah_barang]"
                                    value="1"
                                    min="1">
                                <input type="hidden"
                                    name="barang[${barangId}][id_barang]"
                                    value="${barangId}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-remove">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    }

                    // Reset barang selection
                    $('#barang').prop('selectedIndex', 0);
                });

                // Handle remove button click
                $(document).on('click', '.btn-remove', function() {
                    $(this).closest('tr').remove();
                });

                // Form submission validation
                $('form').on('submit', function(e) {
                    const rows = $('#tableBody tr').length;
                    if (rows === 0) {
                        e.preventDefault();
                        alert('Silakan tambahkan minimal satu barang!');
                        return false;
                    }

                    // Validate quantities
                    let valid = true;
                    $('input[name="qty[]"]').each(function() {
                        const qty = parseInt($(this).val());
                        if (isNaN(qty) || qty < 1) {
                            valid = false;
                            return false; // break loop
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        alert('Jumlah barang harus valid dan minimal 1!');
                        return false;
                    }
                });
            });
        </script>
    @endpush
@endsection
