<?php

namespace App\Http\Controllers\Api\Kebutuhan;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
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
        $kebutuhan = Kebutuhan::with(['posko.user','barang'])->paginate(10);
        return ApiResponse::success($kebutuhan);

    }

    public function show($id)  // id yang digunakan idposko
    {
        $kebutuhan = Kebutuhan::with(['posko.user','barang'])->Where('IDKebutuhan', $id)->first();
        return ApiResponse::success($kebutuhan);
    }

    public function create()
    {
        $kebutuhan = Kebutuhan::groupBy('IDPosko')->pluck('IDPosko');
        $product = Barang::get();
        $posko = Posko::with('user')->whereNotIn('IDPosko', $kebutuhan)->get();
        $data['product'] = $product;
        $data['posko'] = $posko;
        return ApiResponse::success($data);

    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'numeric', // Validasi problem
            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();
            foreach ($request->product as $product) {
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
                return ApiResponse::created($kebutuhan);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data posko tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }

public function qtyReceived(Request $request, $id) // untuk mengisi jumlah yang diterima
    {
        try {
            $validator = Validator::make($request->all(), [
                'qty' => 'numeric', // Validasi problem
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422); // Gunakan status 422 untuk validasi gagal
            }

            DB::beginTransaction();
            $kebutuhan = Kebutuhan::where('IDKebutuhan', $id)->lockForUpdate()->update([
                'JumlahDiterima' => $request->qty,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($kebutuhan) {
                DB::commit();
                $data_kebutuhan = Kebutuhan::where('IDKebutuhan', $id)->first();
                return ApiResponse::success($data_kebutuhan);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data posko tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }
}
