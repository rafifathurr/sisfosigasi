<?php

namespace App\Http\Controllers\Api\Pengungsi;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Penduduk\Penduduk;
use App\Models\Pengungsi\Pengungsi;
use App\Models\Posko\Posko;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengungsiController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin Access
         */
        $this->middleware('role:posko-utama|posko', ['except' => ['index', 'show']]);

        /**
         * Super Admin and Pemerintah Access
         */
        $this->middleware('role:posko-utama|posko', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }
    public function index()
    {
        $pengungsi = Pengungsi::with(['penduduk', 'posko'])->paginate(10);
        return ApiResponse::success($pengungsi);
    }

    public function show($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        return ApiResponse::success($pengungsi);
    }

    public function create(Request $request)
    {
        $penduduk = Penduduk::get();
        $posko = Posko::with('pengguna')->get();
        $data['penduduk'] = $penduduk;
        $data['posko'] = $posko;
        return ApiResponse::success($data);
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

            DB::beginTransaction();
            $pengungsi = Pengungsi::lockForUpdate()->create([
                'IDPenduduk' => $request->idPenduduk,
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

    public function edit($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        $penduduk = Penduduk::get();
        $posko = Posko::with('pengguna')->get();
        $data['penduduk'] = $penduduk;
        $data['posko'] = $posko;
        $data['pengungsi'] = $pengungsi;
        return ApiResponse::success($data);
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
