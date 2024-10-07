<?php

namespace App\Http\Controllers\Api\Pengungsi;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Pengungsi\Pengungsi;
use App\Models\Posko\Posko;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengungsiController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:posko-utama|posko');
    }

    public function index()
    {
        $pengungsi = Pengungsi::with(['penduduk', 'posko'])->paginate(10);

        return ApiResponse::success($pengungsi);
    }

    public function show($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        if(!$pengungsi){
            return ApiResponse::badRequest('Data pengungsi tidak ditemukan.');
        }

        return ApiResponse::success($pengungsi);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'required|numeric', // Validasi nomor kontak
                'idPenduduk' => 'required|numeric', // Validasi nomor kontak
                'condition' => 'string|max:255', // Validasi problem
            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            $posko = Posko::where('IDPosko', $request->idPosko)->first();
            $user = User::where('id', $request->idPenduduk)->first();
            if (!$posko){
                return ApiResponse::badRequest('posko tidak ditemkan');
            }

            if (!$user){
                return ApiResponse::badRequest('user tidak ditemkan');
            }


            DB::beginTransaction();
            $pengungsi = Pengungsi::lockForUpdate()->create([
                'IDPenduduk' => $request->idPenduduk, // ini adalah id user
                'IDPosko' => $request->idPosko,
                'KondisiKhusus' => $request->condition,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($pengungsi) {
                DB::commit();
                return ApiResponse::created($pengungsi);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data pengungsi tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            if (!$id) {
                return ApiResponse::badRequest('error id missing');
            }
            $validator = Validator::make($request->all(), [
                'idPosko' => 'required|numeric', // Validasi nomor kontak
                'idPenduduk' => 'required|numeric', // Validasi nomor kontak
                'condition' => 'string|max:255', // Validasi problem
            ]);


            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }
            
            $posko = Posko::where('IDPosko', $request->idPosko)->first();
            $user = User::where('id', $request->idPenduduk)->first();

            if (!$posko){
                return ApiResponse::badRequest('posko tidak ditemkan');
            }

            if (!$user){
                return ApiResponse::badRequest('user tidak ditemkan');
            }


            DB::beginTransaction();
            $pengungsi = Pengungsi::where('IDPengungsi', $id)->lockForUpdate()->update([
                'IDPenduduk' => $request->idPenduduk,
                'IDPosko' => $request->idPosko,
                'KondisiKhusus' => $request->condition,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($pengungsi) {
                DB::commit();
                $data_pengungsi = Pengungsi::where('IDPengungsi', $id)->first();
                return ApiResponse::success($data_pengungsi);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('pengungsi tidak ditemukan');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }
}
