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
        // menampilkan data kebutuhan dengan dibatasi 10 record
        $kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->paginate(10);
        return ApiResponse::success($kebutuhan);
    }

    public function show($id)  // id yang digunakan idposko
    {
        // tampilan data berdasarkan id posko
        $kebutuhan = Kebutuhan::with(['posko.user', 'barang.jenisBarang'])->where('IDKebutuhan', $id)->first();
        if (!$kebutuhan) {
            return ApiResponse::badRequest('Data kebutuhan tidak ditemukan.');
        }
        return ApiResponse::success($kebutuhan);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [// cek validasi sesuai parameter
                'idPosko' => 'numeric',
            ]);

            if ($validator->fails()) { // jika parameter ada yang tidak sesuai maka return error
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();
            $arr_kebutuhan = [];// menampung data
            foreach ($request->product as $product) {
                $kebutuhan = Kebutuhan::lockForUpdate()->create([
                    'IDBarang' => $product['idProduct'],
                    'IDPosko' => $request->idPosko,
                    'JumlahKebutuhan' => $product['qty'],
                    'LastUpdateDate' => Carbon::now(),
                    'LastUpdateBy' => Auth::user()->id,
                ]);

                if (!$kebutuhan) { // jika kenutuhan ada error, maka batalkan update dan return error
                    DB::rollBack();
                    return ApiResponse::badRequest('Data kebutuhan tidak dapat disimpan.');
                }

                array_push($arr_kebutuhan, $kebutuhan);// jika tidak error maka mengirimkan array
            }

            DB::commit();
            return ApiResponse::created($arr_kebutuhan);// memnuat record baru
        } catch (Exception $e) {
            return ApiResponse::badRequest($e);
        }
    }

    public function qtyReceived(Request $request, $id) // untuk mengisi jumlah yang diterima
    {
        try {
            $validator = Validator::make($request->all(), [
                'qty' => 'numeric', 
            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction();
            // update mengiriman data
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
                'idPosko' => 'numeric',
            ]);

            if ($validator->fails()) {
                return ApiResponse::badRequest($validator->errors());
            }

            DB::beginTransaction(); // memulai transaksi

            // update data kebutuhan
            $kebutuhan = Kebutuhan::where('IDKebutuhan', $id)->lockForUpdate()->update([
                'IDBarang' => $request->idProduct,
                'IDPosko' => $request->idPosko,
                'JumlahKebutuhan' => $request->qtyRequest,
                'JumlahDiterima' => $request->qtyReceived,
                'LastUpdateDate' => Carbon::now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);
            if ($kebutuhan) {
                DB::commit();// jika berhasil maka commit data
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
