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
        // menampilkan seluruh data pengungsi dengan dibatasi per 10 data
        $pengungsi = Pengungsi::with(['penduduk', 'posko.user'])->paginate(10);

        return ApiResponse::success($pengungsi);
    }

    public function show($id)
    {
        // menampilkan detail pengungsi, degan relasi pendusuk, posko dan user
        $pengungsi = Pengungsi::with(['penduduk', 'posko.user'])->where('IDPengungsi', $id)->first();
        if(!$pengungsi){
            return ApiResponse::badRequest('Data pengungsi tidak ditemukan.');
        }

        return ApiResponse::success($pengungsi);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [ // validasi parameter
                'idPosko' => 'required|numeric', 
                'idPenduduk' => 'required|numeric', 
                'condition' => 'string|max:255', 
            ]);

            if ($validator->fails()) {// jika parameter ada yang tidak sesuai dengan aturan, maka masuk kondisi error
                return ApiResponse::badRequest($validator->errors());
            }

            $posko = Posko::where('IDPosko', $request->idPosko)->first();
            $user = User::where('id', $request->idPenduduk)->first();
            if (!$posko){ // cek apakah posko ada, jika tidak ada maka return error
                return ApiResponse::badRequest('posko tidak ditemkan');
            }

            if (!$user){// cek apakah user ada, jika tidak ada maka return error
                return ApiResponse::badRequest('user tidak ditemkan');
            }


            DB::beginTransaction(); // memulai transaksi
            $pengungsi = Pengungsi::lockForUpdate()->create([ // membuat record baru 
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

            if (!$id) { // cek apakah parameter id ada
                return ApiResponse::badRequest('error id missing');
            }
            $validator = Validator::make($request->all(), [ // cek validasi
                'idPosko' => 'required|numeric',
                'idPenduduk' => 'required|numeric', 
                'condition' => 'string|max:255', 
            ]);


            if ($validator->fails()) { // jika ada validasi yang tidak sesuai maka return error
                return ApiResponse::badRequest($validator->errors());
            }

            $posko = Posko::where('IDPosko', $request->idPosko)->first();
            $user = User::where('id', $request->idPenduduk)->first();

            if (!$posko){ // jika posko tidak ada maka retur error
                return ApiResponse::badRequest('posko tidak ditemkan');
            }

            if (!$user){ // jika user tidak ada maka muncul error
                return ApiResponse::badRequest('user tidak ditemkan');
            }


            DB::beginTransaction(); // memulai transaksi
            $pengungsi = Pengungsi::where('IDPengungsi', $id)->lockForUpdate()->update([ // update record berdasarkan id pengungsi
                'IDPenduduk' => $request->idPenduduk,
                'IDPosko' => $request->idPosko,
                'KondisiKhusus' => $request->condition,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($pengungsi) {
                DB::commit();
                $data_pengungsi = Pengungsi::with(['penduduk', 'posko.user'])->where('IDPengungsi', $id)->first();
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
