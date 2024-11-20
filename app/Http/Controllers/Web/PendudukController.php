<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Kelompok\KelompokController;
use App\Http\Controllers\Api\Penduduk\PendudukController as PendudukPendudukController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PendudukController extends Controller
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
        $response = (new PendudukPendudukController)->index();
        $response_body = json_decode($response->content());
        $data = $response_body->data->data;

        return view('penduduk.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = (new KelompokController)->index();
        $response_body = json_decode($response->content());
        $kelompoks = $response_body->data->data;

        return view('penduduk.create', compact('kelompoks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = (new PendudukPendudukController)->store($request);
        $response_body = json_decode($response->content());

        if ($response_body->status == 201) {

            return redirect()->route('kelompok.index')->with('success', "Penduduk Berhasil Dibuat");
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = (new PendudukPendudukController)->show($id);
        $response_body = json_decode($response->content());
        $penduduk = $response_body->data;

        return view('penduduk.view', compact('penduduk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = (new PendudukPendudukController)->show($id);
        $response_body = json_decode($response->content());
        $penduduk = $response_body->data;

        $response = (new KelompokController)->index();
        $response_body = json_decode($response->content());
        $kelompoks = $response_body->data->data;

        return view('penduduk.edit', compact('penduduk', 'kelompoks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = (new PendudukPendudukController)->update($request, $id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('penduduk.index')->with('success', "Penduduk Berhasil Dirubah");
        }

        return redirect()->route('penduduk.index')->with('failed', "Penduduk Gagal Dirubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = (new PendudukPendudukController)->delete($id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('penduduk.index')->with('success', "Penduduk Berhasil Dihapus");
        }

        return redirect()->route('penduduk.index')->with('failed', "Penduduk Gagal Dihapus");
    }
}
