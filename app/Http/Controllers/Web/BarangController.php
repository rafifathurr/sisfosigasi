<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Kecamatan\BarangController as KecamatanBarangController;
use App\Http\Controllers\Api\Kecamatan\JenisBarangController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.check');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response =  (new KecamatanBarangController)->index();
        $response_body = json_decode($response->content());
        $data = $response_body->data->data;

        return view('barang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = (new JenisBarangController)->index();
        $response_body = json_decode($response->content());
        $jenis_barangs = $response_body->data->data;

        return view('barang.create', compact('jenis_barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = (new KecamatanBarangController)->store($request);
        $response_body = json_decode($response->content());

        if ($response_body->status == 201) {

            return redirect()->route('barang.index');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = (new KecamatanBarangController)->show($id);
        $response_body = json_decode($response->content());
        $barang = $response_body->data;

        return view('barang.view', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = (new KecamatanBarangController)->show($id);
        $response_body = json_decode($response->content());
        $barang = $response_body->data;

        $response = (new JenisBarangController)->index();
        $response_body = json_decode($response->content());
        $jenis_barangs = $response_body->data->data;

        return view('barang.edit', compact('barang', 'jenis_barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = (new KecamatanBarangController)->update($request, $id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('barang.index');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response =  (new KecamatanBarangController)->delete($id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('barang.index')->with('success', "Data Berhasil Dihapus");
        }

        return redirect()->route('barang.index')->with('failed', 'Gagal Update Data');
    }
}
