<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\Barang\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $jenis_barang = JenisBarang::all();

            if (!is_null($jenis_barang)) {

                return response()->json($jenis_barang, 200);
            }

            return response()->json(404);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'jenis_barang' => 'required|string|max:15',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $store_jenis_barang = JenisBarang::lockForUpdate()->create([
                'JenisBarang' => $request->jenis_barang,
                'LastUpdateDate' => now()
            ]);

            if ($store_jenis_barang) {

                return response()->json(201);
            }

            return response()->json(400);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $jenis_barang = JenisBarang::where('IDJenisBarang', $id)->first();

            if (!is_null($jenis_barang)) {

                return response()->json($jenis_barang, 200);
            }

            return response()->json($jenis_barang, 400);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'jenis_barang' => 'required|string|max:15',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $update_jenis_barang = JenisBarang::where('IDJenisBarang', $id)->update([
                'JenisBarang' => $request->jenis_barang,
                'LastUpdateDate' => now()
            ]);

            if ($update_jenis_barang == 1) {

                return response()->json(200);
            }

            return response()->json(400);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
