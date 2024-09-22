<?php

namespace App\Http\Controllers\Bantuan;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Bantuan\Bantuan;
use App\Models\Bantuan\Bantuan_Dtl;
use App\Models\Barang\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BantuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:kecematan');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $bantuan = Bantuan::with([
                'donatur'
            ])
                ->orderBy('IDBantuan', 'desc')
                ->get();

            if (!is_null($bantuan)) {

                return ApiResponse::success($bantuan);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
        }
    }

    public function createOrEdit()
    {
        try {

            $barang = Barang::all();

            if ($barang->isEmpty()) {

                return ApiResponse::success($barang);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'donatur' => 'integer|exists:donatur,IDDonatur',
                'tanggal_bantuan' => 'date|nullable',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bantuan = Bantuan::lockForUpdate()->create([
                'IDDonatur' => $request->donatur,
                'TanggalBantuan' => $request->tanggal_bantuan,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);

            if ($bantuan) {

                foreach ($request->barang as $item) {

                    $bantuan_detail = Bantuan_Dtl::lockForUpdate()->insert([
                        'IDBantuan' => $bantuan->IDBantuan,
                        'IDBarang' => $item->id_barang,
                        'Jumlah' => $item->jumlah_barang,
                    ]);
                }

                if ($bantuan_detail) {

                    DB::commit();
                    return ApiResponse::created($bantuan);
                } else {

                    DB::rollback();
                    return ApiResponse::badRequest();
                }
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $bantuan = Bantuan::with([
                'donatur'
            ])
                ->where('IDBantuan', $id)
                ->orderBy('IDBantuan', 'desc')
                ->get();

            if (!is_null($bantuan)) {

                return ApiResponse::success($bantuan);
            }

            return ApiResponse::notFound();
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'donatur' => 'integer|exists:donatur,IDDonatur',
                'tanggal_bantuan' => 'date|nullable',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $bantuan = Bantuan::lockForUpdate()->where('IDBantuan', $id)->update([
                'IDDonatur' => $request->donatur,
                'TanggalBantuan' => $request->tanggal_bantuan,
                'LastUpdateDate' => now(),
                'LastUpdateBy' => Auth::user()->id,
            ]);

            if ($bantuan) {

                $old_bantuan_detail = Bantuan_Dtl::where('IDBantuan', $id)->get()->pluck('IDBarang');
                $new_bantuan_detail = collect($request->barang)->pluck('id_barang')->toArray();

                $to_insert = array_diff($new_bantuan_detail, $old_bantuan_detail);
                $to_update = array_intersect($old_bantuan_detail, $new_bantuan_detail);
                $to_delete = array_diff($old_bantuan_detail, $new_bantuan_detail);

                if (!empty($to_delete)) {
                    Bantuan_Dtl::lockForUpdate()->where('IDBantuan', $id)
                        ->whereIn('IDBarang', $to_delete)
                        ->delete();
                }

                foreach ($request->barang as $item) {
                    if (in_array($item['id_barang'], $to_insert)) {
                        Bantuan_Dtl::lockForUpdate()->insert([
                            'IDBantuan' => $bantuan->IDBantuan,
                            'IDBarang' => $item['id_barang'],
                            'Jumlah' => $item['jumlah_barang'],
                        ]);
                    }
                }

                foreach ($request->barang as $item) {
                    if (in_array($item['id_barang'], $to_update)) {
                        Bantuan_Dtl::lockForUpdate()->where('IDBantuan', $id)
                            ->where('IDBarang', $item['id_barang'])
                            ->update([
                                'Jumlah' => $item['jumlah_barang']
                            ]);
                    }
                }

                DB::commit();
                return ApiResponse::success($bantuan);
            }

            DB::rollback();
            return ApiResponse::badRequest();
        } catch (\Throwable $th) {

            DB::rollback();
            return ApiResponse::badRequest($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
