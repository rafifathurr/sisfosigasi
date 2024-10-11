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

            $barang = Barang::with([
                'jenisBarang'
            ])->paginate(10);

            return ApiResponse::success($barang);
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
                'nama_barang' => 'required|string|max:255',
                'jenis_barang' => 'required|integer|max:15',
                'harga_satuan' => 'required|integer',
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

                DB::commit();
                return ApiResponse::created($barang);
            } else {

                DB::rollback();
                return ApiResponse::badRequest();
            }
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {

            $barang = Barang::where('IDBarang', $id)->first();

            if (!is_null($barang)) {

                return ApiResponse::success($barang);
            }

            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255',
                'jenis_barang' => 'required|integer|max:15',
                'harga_satuan' => 'required|integer',
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
                return ApiResponse::success(Barang::where('IDBarang', $id)->first());
            }

            DB::rollBack();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
