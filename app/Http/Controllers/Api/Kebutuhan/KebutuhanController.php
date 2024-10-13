<?php

namespace App\Http\Controllers\Api\Kebutuhan;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Kebutuhan\Kebutuhan;
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
        $this->middleware('role:posko-utama|posko');
    }

    public function index()
    {
        $kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->paginate(10);
        return ApiResponse::success($kebutuhan);
    }

    public function show($id)  // id yang digunakan idposko
    {
        $kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->where('IDKebutuhan', $id)->first();
        if (!$kebutuhan) {
            return ApiResponse::badRequest('Data kebutuhan tidak ditemukan.');
        }
        return ApiResponse::success($kebutuhan);
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
            $arr_kebutuhan = [];
            foreach ($request->product as $product) {
                $kebutuhan = Kebutuhan::lockForUpdate()->create([
                    'IDBarang' => $product['idProduct'],
                    'IDPosko' => $request->idPosko,
                    'JumlahKebutuhan' => $product['qty'],
                    'LastUpdateDate' => Carbon::now(),
                    'LastUpdateBy' => Auth::user()->id,
                ]);

                if (!$kebutuhan) {
                    DB::rollBack();
                    return ApiResponse::badRequest('Data kebutuhan tidak dapat disimpan.');
                }

                array_push($arr_kebutuhan, $kebutuhan);
            }

            DB::commit();
            return ApiResponse::created($arr_kebutuhan);
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
                $data_kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->where('IDKebutuhan', $id)->first();
                return ApiResponse::success($data_kebutuhan);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data kebutuhan tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }

    public function update(Request $request, $id) // untuk mengisi jumlah yang diterima
    {
        try {
            $validator = Validator::make($request->all(), [
                'idPosko' => 'numeric', // Validasi problem
            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();

            $kebutuhan = Kebutuhan::where('IDKebutuhan', $id)->lockForUpdate()->update([
                'IDBarang' => $request->idProduct,
                'IDPosko' => $request->idPosko,
                'JumlahKebutuhan' => $request->qtyRequest,
                'JumlahDiterima' => $request->qtyReceived,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($kebutuhan) {
                DB::commit();
                $data_kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->where('IDKebutuhan', $id)->with('barang', 'posko')->first();
                return ApiResponse::created($data_kebutuhan);
            } else {
                DB::rollBack();
                return ApiResponse::badRequest('Data kebutuhan tidak dapat disimpan.');
            }
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }
}
