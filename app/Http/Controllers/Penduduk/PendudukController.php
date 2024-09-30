<?php

namespace App\Http\Controllers\Penduduk;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Penduduk\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PendudukController extends Controller
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

            $penduduk = Penduduk::all();

            if ($penduduk->isNotEmpty()) {

                return ApiResponse::success($penduduk);
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
                'ktp' => 'nullable|string|max:16',
                'nama' => 'required|string|max:20',
                'alamat' => 'required|string|max:50',
                'desa' => 'required|string|max:20',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|boolean',
                'kelompok' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $store =  Penduduk::lockForUpdate()->create([
                'KTP' => $request->ktp,
                'Nama' => $request->nama,
                'Alamat' => $request->alamat,
                'Desa' => $request->desa,
                'TanggalLahir' => $request->tanggal_lahir,
                'JenisKelamin' => $request->jenis_kelamin,
                'Kelompok' => $request->kelompok,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => auth()->user()->id,
            ]);

            if ($store) {

                DB::commit();
                return ApiResponse::created();
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $penduduk = Penduduk::where('IDPenduduk', $id)->first();

            if (!is_null($penduduk)) {

                return ApiResponse::success($penduduk);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
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
                'ktp' => 'nullable|string|max:16',
                'nama' => 'required|string|max:20',
                'alamat' => 'required|string|max:50',
                'desa' => 'required|string|max:20',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|boolean',
                'kelompok' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $store =  Penduduk::lockForUpdate()->where('IDPenduduk', $id)->update([
                'KTP' => $request->ktp,
                'Nama' => $request->nama,
                'Alamat' => $request->alamat,
                'Desa' => $request->desa,
                'TanggalLahir' => $request->tanggal_lahir,
                'JenisKelamin' => $request->jenis_kelamin,
                'Kelompok' => $request->kelompok,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => auth()->user()->id,
            ]);

            if ($store) {

                DB::commit();
                return ApiResponse::success();
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
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
