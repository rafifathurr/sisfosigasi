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
                            <h3 class="card-title">Tambah Bantuan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('bantuan.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Donatur
                                        </label>
                                        <select class="form-select" id="validationCustom04" name="donatur" required>
                                            @if (is_null($donaturs))
                                                <option selected disabled value="">Data Tidak ada</option>
                                            @else
                                                <option selected disabled value="">Pilih Donatur</option>
                                                @foreach ($donaturs as $item)
                                                    <option value="{{ $item->IDDonatur }}">{{ $item->NamaPerusahaan }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label for="exampleInputEmail1" class="form-label">
                                            Barang
                                        </label>
                                        <div class="input-group">
                                            <select class="form-select" id="barang" name="barang">
                                                @if (is_null($barangs))
                                                    <option selected disabled value="">Data Tidak ada</option>
                                                @else
                                                    <option selected disabled value="">Pilih Barang</option>
                                                    @foreach ($barangs as $item)
                                                        <option value="{{ $item->IDBarang }}">{{ $item->NamaBarang }}
                                                        </option>
                                                    @endforeach
                                                @endif
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
                                        <input type="date" name="tanggal_bantuan" class="form-control">
                                    </div>
                                </div>
                                <h5 class="mt-4">List Barang</h5>
                                <table class="table">
                                    <thead>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </thead>
                                    <tbody id="tableBody">

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
