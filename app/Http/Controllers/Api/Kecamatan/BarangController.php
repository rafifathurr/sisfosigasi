<?php

namespace App\Http\Controllers\Api\Kecamatan;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Barang\Barang;
use App\Models\Barang\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:kecamatan');
    }

    public function index()
    {
        try {
            // Mengambil data barang beserta relasi 'jenisBarang' menggunakan eager loading
            $barang = Barang::with([
                'jenisBarang' // Memuat relasi 'jenisBarang' untuk setiap barang
            ])->paginate(10); // Membatasi hasil menjadi 10 data per halaman

            // Mengembalikan respons sukses dengan data barang yang dipaginasi
            return ApiResponse::success($barang);
        } catch (\Throwable $th) {
            // Menangkap exception dan mengembalikan pesan error dengan status 500 (internal server error)
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Validasi input dari request
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255', // nama_barang wajib, maksimal 255 karakter
                'jenis_barang' => 'required|integer|max:15', // jenis_barang wajib, harus integer dengan maksimal 15 karakter
                'harga_satuan' => 'required|integer', // harga_satuan wajib, harus integer
            ]);

            // Jika validasi gagal, kembalikan respons error validasi dengan kode 422
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Membuat data barang baru
            $barang = Barang::lockForUpdate()->create([
                'NamaBarang' => $request->nama_barang,
                'IDJenisBarang' => $request->jenis_barang,
                'HargaSatuan' => $request->harga_satuan,
                'LastUpdateDate' => now(),
            ]);

            // Jika berhasil, komit transaksi dan kembalikan respons sukses
            if ($barang) {
                DB::commit();
                return ApiResponse::created($barang);
            } else {
                // Jika gagal, rollback transaksi
                DB::rollback();
                return ApiResponse::badRequest();
            }
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi exception
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }


    public function show($id)
    {
        try {

            $barang = Barang::with([
                'jenisBarang'
            ])->where('IDBarang', $id)->first();

            // Jika data barang ditemukan
            if (!is_null($barang)) {
                return ApiResponse::success($barang); // Mengembalikan respons sukses dengan data barang
            }

            // Jika tidak ditemukan, kembalikan respons bad request
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {
            // Menangkap exception dan mengembalikan pesan error dengan status 500
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            // Memulai transaksi database
            DB::beginTransaction();

            // Validasi input dari request
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255', // nama_barang wajib, maksimal 255 karakter
                'jenis_barang' => 'required|integer|max:15', // jenis_barang wajib, harus integer dengan maksimal 15 karakter
                'harga_satuan' => 'required|integer', // harga_satuan wajib, harus integer
            ]);

            // Jika validasi gagal, kembalikan respons error validasi dengan kode 422
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Mengupdate data barang berdasarkan IDBarang
            $update_barang = Barang::where('IDBarang', $id)->update([
                'NamaBarang' => $request->nama_barang,
                'IDJenisBarang' => $request->jenis_barang,
                'HargaSatuan' => $request->harga_satuan,
                'LastUpdateDate' => now(),
            ]);

            // Jika berhasil diupdate, komit transaksi dan kembalikan respons sukses
            if ($update_barang == 1) {
                DB::commit();
                return ApiResponse::success(Barang::with([
                    'jenisBarang'
                ])->where('IDBarang', $id)->first());
            }

            // Jika gagal, rollback transaksi
            DB::rollBack();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi exception
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
