<?php

namespace App\Http\Controllers\Pengungsi;

use App\Http\Controllers\Controller;
use App\Models\Pengungsi\Pengungsi;
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
        $this->middleware('role:posko-utama', ['except' => ['index', 'show']]);

        /**
         * Super Admin and Pemerintah Access
         */
        $this->middleware('role:posko-utama|pemerintah', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }
    public function index()
    {
        $pengungsi = Pengungsi::with(['penduduk', 'posko'])->get();
        return response()->json($pengungsi, 200); // Mengembalikan response JSON dengan status 201

    }

    public function show($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        return response()->json($pengungsi, 200); // Mengembalikan response JSON dengan status 200

    }

    // public function create(Request $request)
    // {
    //     $pengguna = Pengguna::whereNull('IdPosko')->get();
    //     return response()->json($pengguna, 200); // Mengembalikan response JSON dengan status 201
    // }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'required|numeric|max:50', // Validasi nomor kontak
                'condition' => 'string|max:255', // Validasi problem
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
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
                    return response()->json('data berhasil disimpan', 200); // Mengembalikan response JSON dengan status 201
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Data posko tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Data tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
            return response()->json(['error' => $e], 500); // Mengembalikan error jika terjadi pengecualian

        }
    }

    // public function edit($id)
    // {
    //     $posko = Posko::where('IDPosko', $id)->with('pengguna')->first();
    //     $pengguna = Pengguna::whereNull('IdPosko')->get();
    //     $data['posko'] = $posko;
    //     $data['pengguna'] = $pengguna;
    //     return response()->json($data, 200); // Mengembalikan response JSON dengan status 200

    // }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'idPengguna' => 'required|string|max:255', // Validasi nama
    //             'location' => 'required|string|max:50', // Validasi nomor kontak
    //             'problem' => 'required|string|max:255', // Validasi problem
    //             'solution' => 'required|string|max:255', // Validasi problem

    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
    //         }

    //         DB::beginTransaction();
    //         $posko = Posko::where('IDPosko', $id)->lockForUpdate()->update([
    //             'Ketua' => $request->idPengguna,
    //             'Lokasi' => $request->location,
    //             'Masalah' => $request->problem,
    //             'SolusiMasalah' => $request->solution,

    //         ]);
    //         if ($posko) {
    //             $pengguna = Pengguna::where('IDPengguna', $request->idPengguna)->lockForUpdate()->update([
    //                 'IDPosko' => $id
    //             ]);
    //             if ($pengguna) {
    //                 DB::commit();
    //                 return response()->json('data berhasil diupdate', 201); // Mengembalikan response JSON dengan status 201
    //             }else{
    //                 DB::rollBack();
    //                 return response()->json(['error' => 'Data pengguna tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian

    //             }
    //         } else {
    //             DB::rollBack();
    //             return response()->json(['error' => 'Data posko tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Data tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
    //         return response()->json(['error' => $e], 500); // Mengembalikan error jika terjadi pengecualian

    //     }

    // }
}
