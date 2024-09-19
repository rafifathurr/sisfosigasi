<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Pengguna\Pengguna;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin Access
         */
        $this->middleware('role:posko-utama', ['except' => ['index', 'show']]);

        /**
         * Super Admin and Pemerintah Access
         */
        $this->middleware('role:posko-utama|pemerintah', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', // Validasi nama
                'phone' => 'required|string|max:15', // Validasi nomor kontak
                'satuan' => 'required|string|max:50', // Validasi satuan
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            $pengguna = Pengguna::lockForUpdate()->create([
                'Nama' => $request->name,
                'NomorKontak' => $request->phone,
                'Satuan' => $request->satuan,
            ]);

            return response()->json($pengguna, 201); // Mengembalikan response JSON dengan status 201
        } catch (Exception $e) {
            return response()->json(['error' => 'Data tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
            return response()->json(['error' => $e], 500); // Mengembalikan error jika terjadi pengecualian

        }

    }
}
