<?php

namespace App\Http\Controllers\Api\Kecamatan;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Barang\JenisBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JenisBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:kecamatan');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $jenis_barang = JenisBarang::paginate(10);

            return ApiResponse::success($jenis_barang);

            return ApiResponse::badRequest();
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

                DB::commit();
                return ApiResponse::created();
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
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

                return ApiResponse::success($jenis_barang);
            }

            return ApiResponse::badRequest();
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

                DB::commit();
                return ApiResponse::success(JenisBarang::where('IDJenisBarang', $id)->first());
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
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
