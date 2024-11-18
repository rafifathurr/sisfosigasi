<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Kelompok\KelompokController as KelompokKelompokController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KelompokController extends Controller
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
        $response =  (new KelompokKelompokController)->index();
        $response_body = json_decode($response->content());
        $data = $response_body->data->data;

        return view('kelompok.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kelompok.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = (new KelompokKelompokController)->store($request);
        $response_body = json_decode($response->content());

        if ($response_body->status == 201) {

            return redirect()->route('kelompok.index')->with('success', "Data Berhasil Dibuat");
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = (new KelompokKelompokController)->show($id);
        $response_body = json_decode($response->content());
        $data = $response_body->data;

        return view('kelompok.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = (new KelompokKelompokController)->show($id);
        $response_body = json_decode($response->content());
        $data = $response_body->data;

        return view('kelompok.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = (new KelompokKelompokController)->update($request, $id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('kelompok.index')->with('success', "Data Berhasil Dirubah");
        }

        return redirect()->route('kelompok.index')->with('failed', 'Gagal Update Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = (new KelompokKelompokController)->delete($id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('kelompok.index')->with('success', "Data Berhasil Dihapus");
        }

        return redirect()->route('kelompok.index')->with('failed', 'Gagal Update Data');
    }
}
