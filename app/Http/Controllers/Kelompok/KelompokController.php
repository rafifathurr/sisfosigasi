<?php

namespace App\Http\Controllers\Kelompok;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Kelompok\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelompokController extends Controller
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

            $kelompok = Kelompok::all();

            if ($kelompok->isNotEmpty()) {

                return ApiResponse::success($kelompok);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
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
                'nama_kelompok' => 'required|string|max:20',
                'keterangan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $store = Kelompok::lockForUpdate()->create([
                'NamaKelompok' => $request->nama_kelompok,
                'Keterangan' => $request->keterangan
            ]);

            if ($store) {
                DB::commit();
                return ApiResponse::created();
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $kelompok = Kelompok::where('IDKelompok', $id)->first();

            if (!is_null($kelompok)) {

                return ApiResponse::success($kelompok);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {
            //throw $th;
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
                'nama_kelompok' => 'required|string|max:20',
                'keterangan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $update = Kelompok::lockForUpdate()->where('IDKelompok', $id)->update([
                'NamaKelompok' => $request->nama_kelompok,
                'Keterangan' => $request->keterangan
            ]);

            if ($update) {
                DB::commit();
                return ApiResponse::success();
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
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
