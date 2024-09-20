<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\Barang\Barang;
use App\Models\Barang\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {

        try {

            $barang = Barang::all();

            if (!is_null($barang)) {

                return response()->json($barang, 200);
            } else {

                return response()->json(401);
            }
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
            //throw $th;
        }
    }

    public function store(Request $request)
    {

        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nama_barnag' => 'required|string|max:255',
                'jenis_barang' => 'required|integer|max:15',
                'harga_satuan' => 'required|integer|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            $barang = Barang::lockForUpdate()->create([
                'NamaBarang' => $request->nama_barang,
                'IDJenisBarang' => $request->jenis_barang,
                'HargaSatuan' => $request->harga_satuan,
                'LastUpdateDate' => now(),
            ]);

            if ($barang) {

                return response()->json(201);
            } else {

                return response()->json(400);
            }
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {

            $barang = Barang::where('IDBarang', $id)->first();

            if (!is_null($barang)) {

                return response()->json($barang, 200);
            }

            return response()->json(404);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nama_barnag' => 'required|string|max:255',
                'jenis_barang' => 'required|integer|max:15',
                'harga_satuan' => 'required|integer|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            $update_barang = Barang::where('IDBarang', $id)->update([
                'NamaBarang' => $request->nama_barang,
                'IDJenisBarang' => $request->jenis_barang,
                'HargaSatuan' => $request->harga_satuan,
                'LastUpdateDate' => now(),
            ]);

            if ($update_barang == 1) {

                DB::commit();
                return response()->json(200);
            }

            DB::rollBack();
            return response()->json(400);
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
