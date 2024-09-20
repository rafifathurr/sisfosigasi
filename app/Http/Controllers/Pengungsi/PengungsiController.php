<?php

namespace App\Http\Controllers\Pengungsi;

use App\Http\Controllers\Controller;
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
        $pengungsi = Pengungsi::with(['penduduk', 'posko'])->get();
        return response()->json($pengungsi, 200); // Mengembalikan response JSON dengan status 201

    }

    public function show($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        return response()->json($pengungsi, 200); // Mengembalikan response JSON dengan status 200

    }

    public function create(Request $request)
    {
        $penduduk = Penduduk::get();
        $posko = Posko::with('pengguna')->get();
        $data['penduduk'] = $penduduk;
        $data['posko'] = $posko;
        return response()->json($data, 200); // Mengembalikan response JSON dengan status 200
    }

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

    public function edit($id)
    {
        $pengungsi = Pengungsi::where('IDPengungsi', $id)->with(['penduduk', 'posko'])->first();
        $penduduk = Penduduk::get();
        $posko = Posko::with('pengguna')->get();
        $data['penduduk'] = $penduduk;
        $data['posko'] = $posko;
        $data['pengungsi'] = $pengungsi;
        return response()->json($data, 200); // Mengembalikan response JSON dengan status 200

    }

    public function update(Request $request, $id)
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
            $pengungsi = Pengungsi::where('IDPengungsi', $id)->lockForUpdate()->update([
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
}
