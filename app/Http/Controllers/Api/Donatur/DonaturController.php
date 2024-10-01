<?php

namespace App\Http\Controllers\Api\Donatur;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Donatur\Donatur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DonaturController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:bansos');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $donatur = Donatur::paginate(10);

            return ApiResponse::success($donatur);
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
                'nama_perusahaan' => 'nullable|string|max:50',
                'alamat' => 'required|string|max:255',
                'nomor_kontak' => 'required|string|max:16',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $donatur = Donatur::lockForUpdate()->create([
                'NamaPerusahaan' => $request->nama_perusahaan,
                'Alamat' => $request->alamat,
                'NomorKontak' => $request->nomor_kontak,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => Auth::user()->id
            ]);

            if ($donatur) {

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

            $donatur = Donatur::where('IDDonatur', $id)->first();

            if (!is_null($donatur)) {

                return ApiResponse::success($donatur);
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
                'nama_perusahaan' => 'nullable|string|max:50',
                'alamat' => 'required|string|max:255',
                'nomor_kontak' => 'required|string|max:16',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $donatur = Donatur::lockForUpdate()->where('IDDonatur', $id)->update([
                'NamaPerusahaan' => $request->nama_perusahaan,
                'Alamat' => $request->alamat,
                'NomorKontak' => $request->nomor_kontak,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => Auth::user()->id
            ]);

            if ($donatur) {

                DB::commit();
                return ApiResponse::success(Donatur::where('IDDonatur', $id)->first());
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
