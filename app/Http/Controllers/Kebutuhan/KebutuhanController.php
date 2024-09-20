<?php

namespace App\Http\Controllers\Kebutuhan;

use App\Http\Controllers\Controller;
use App\Models\Kebutuhan\Kebutuhan;
use App\Models\Posko\Posko;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KebutuhanController extends Controller
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
        $posko = Posko::whereHas('kebutuhan', function($query) {
            $query->whereNotNull('IDPosko');
        })->get();
        return response()->json($posko, 200); // Mengembalikan response JSON dengan status 201

    }

    public function store(Request $request)
    {
        dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'idProduct' => 'required|numeric|max:50', // Validasi nomor kontak
                'idPosko' => 'string|max:255', // Validasi problem
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            DB::beginTransaction();
            $pengungsi = Kebutuhan::lockForUpdate()->create([
                'IDBarang ' => $request->idProduct,
                'IDPosko ' => $request->idPosko,
                'JumlahKebutuhan' => $request->total,
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
