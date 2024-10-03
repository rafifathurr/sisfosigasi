<?php

namespace App\Http\Controllers\Api\Posko;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Pengguna\Pengguna;
use App\Models\Posko\Posko;
use Exception;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PoskoController extends Controller
{
    public function __construct()
    {
        /**
         * Super Admin Access
         */
        $this->middleware('role:posko-utama', ['except' => ['index', 'show']]);

        /**
         * Super Admin and Pemerintah Access
         */
        $this->middleware('role:posko-utama|posko', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }
    public function index()
    {
        $posko = Posko::with('user')->paginate(10);
        return ApiResponse::success($posko);
    }

    public function show($id)
    {
        $posko = Posko::where('IDPosko', $id)->with('user')->first();
        return ApiResponse::success($posko);
    }

    public function create(Request $request)
    {
        $pengguna = User::get();
        return ApiResponse::success($pengguna);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idUser' => 'required|numeric', // Validasi nama
                'location' => 'required|max:50', // Validasi nomor kontak
                'problem' => 'required', // Validasi problem
                'solution' => 'required', // Validasi problem

            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();
            $posko = Posko::lockForUpdate()->create([
                'Ketua' => $request->idUser,
                'Lokasi' => $request->location,
                'Masalah' => $request->problem,
                'SolusiMasalah' => $request->solution,

            ]);
            if ($posko) {
                DB::commit();
                return ApiResponse::success($posko);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data posko tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest('Data tidak dapat disimpan.');
        }
    }

    public function edit($id)
    {
        $posko = Posko::where('IDPosko', $id)->with('user')->first();
        $pengguna = user::get();
        $data['posko'] = $posko;
        $data['pengguna'] = $pengguna;
        return ApiResponse::success($data);

    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idUser' => 'required', // Validasi nama
                'location' => 'required|max:50', // Validasi nomor kontak
                'problem' => 'required', // Validasi problem
                'solution' => 'required', // Validasi problem

            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();
            $posko = Posko::where('IDPosko', $id)->lockForUpdate()->update([
                'Ketua' => $request->idUser,
                'Lokasi' => $request->location,
                'Masalah' => $request->problem,
                'SolusiMasalah' => $request->solution,

            ]);
            if ($posko) {
                $data_posko =  Posko::where('IDPosko', $id)->first();
                DB::commit();
                return ApiResponse::success($data_posko);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data posko tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }
}
