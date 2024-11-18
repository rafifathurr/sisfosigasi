<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Kecamatan\JenisBarangController as KecamatanJenisBarangController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JenisBarangController extends Controller
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
        $response = (new KecamatanJenisBarangController)->index();
        $response_body = json_decode($response->content());
        $data = $response_body->data->data;

        return view('jenis_barang.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = (new KecamatanJenisBarangController)->store($request);
        $response_body = json_decode($response->content());

        if ($response_body->status == 201) {

            return redirect()->route('jenis-barang.index');
        } else {

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = (new KecamatanJenisBarangController)->show($id);
        $response_body = json_decode($response->content());
        $data = $response_body->data;

        return view('jenis_barang.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = (new KecamatanJenisBarangController)->show($id);
        $response_body = json_decode($response->content());
        $data = $response_body->data;

        return view('jenis_barang.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = (new KecamatanJenisBarangController)->update($request, $id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('jenis-barang.index');
        } else {

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response =  (new KecamatanJenisBarangController)->delete($id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('jenis-barang.index')->with('success', "Data Berhasil Dihapus");
        }

        return redirect()->route('jenis-barang.index')->with('failed', 'Gagal Hapus Data');
    }
}
