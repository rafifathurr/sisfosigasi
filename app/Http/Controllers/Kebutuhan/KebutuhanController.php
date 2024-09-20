<?php

namespace App\Http\Controllers\Kebutuhan;

use App\Http\Controllers\Controller;
use App\Models\Barang\Barang;
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
        return response()->json($posko, 200); // Mengembalikan response JSON dengan status 200

    }

    public function show($id)  // id yang digunakan idposko
    {
        $pengungsi = Posko::where('IDPosko', $id)->with(['pengguna', 'kebutuhan'])->first();
        return response()->json($pengungsi, 200); // Mengembalikan response JSON dengan status 200
    }

    public function create()
    {
        $kebutuhan = Kebutuhan::groupBy('IDPosko')->pluck('IDPosko');
        $product = Barang::get();
        $posko = Posko::with('pengguna')->whereNotIn('IDPosko', $kebutuhan)->get();
        $data['product'] = $product;
        $data['posko'] = $posko;
        return response()->json($data, 200); // Mengembalikan response JSON dengan status 200

    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'string|max:255', // Validasi problem
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            DB::beginTransaction();
            foreach ($request->product as $product){
                $kebutuhan = Kebutuhan::lockForUpdate()->create([
                    'IDBarang' => $product['idProduct'],
                    'IDPosko' => $request->idPosko,
                    'JumlahKebutuhan' => $product['qty'],
                    'LastUpdateDate' => Carbon::now(),
                    'LastUpdateBy' => Auth::user()->id,
                ]);
            }
            if ($kebutuhan) {
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

    public function qtyReceived(Request $request) // untuk mengisi jumlah yang diterima
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'string|max:255', // Validasi problem
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            DB::beginTransaction();
            foreach ($request->product as $product){
                $kebutuhan = Kebutuhan::where('IDPosko', $request->idPosko)->where('IDBarang', $product['idProduct'])->lockForUpdate()->update([
                    'JumlahDiterima' => $product['qty'],
                    'LastUpdateDate' => Carbon::now(),
                    'LastUpdateBy' => Auth::user()->id,
                ]);
            }
            if ($kebutuhan) {
                DB::commit();
                return response()->json('data berhasil disimpan', 200); // Mengembalikan response JSON dengan status 201
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Data posko tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
            }
        } catch (Exception $e) {
            dd($e);
            return response()->json(['error' => 'Data tidak dapat disimpan.'], 500); // Mengembalikan error jika terjadi pengecualian
            return response()->json(['error' => $e], 500); // Mengembalikan error jika terjadi pengecualian
        }
    }

}
