<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Bantuan\BantuanController as BantuanBantuanController;
use App\Http\Controllers\Api\Donatur\DonaturController;
use App\Http\Controllers\Api\Kecamatan\BarangController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BantuanController extends Controller
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
        $response = (new BantuanBantuanController)->index();
        $response_body = json_decode($response->content());
        $data = $response_body->data->data;

        return view('bantuan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = (new DonaturController)->index();
        $response_body = json_decode($response->content());
        $donaturs = $response_body->data->data;

        $response = (new BarangController)->index();
        $response_body = json_decode($response->content());
        $barangs = $response_body->data->data;

        return view('bantuan.create', compact('donaturs', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = (new BantuanBantuanController)->store($request);
        $response_body = json_decode($response->content());

        if ($response_body->status == 201) {

            return redirect()->route('bantuan.index')->with('success', 'Bantuan Berhasil Di-input');
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = (new BantuanBantuanController)->show($id);
        $response_body = json_decode($response->content());
        $bantuan = $response_body->data;

        return view('bantuan.view', compact('bantuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = (new DonaturController)->index();
        $response_body = json_decode($response->content());
        $donaturs = $response_body->data->data;

        $response = (new BarangController)->index();
        $response_body = json_decode($response->content());
        $barangs = $response_body->data->data;

        $response = (new BantuanBantuanController)->show($id);
        $response_body = json_decode($response->content());
        $bantuan = $response_body->data;

        return view('bantuan.edit', compact('bantuan', 'barangs', 'donaturs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = (new BantuanBantuanController)->update($request, $id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('bantuan.index')->with('success', 'Bantuan Berhasil Di-Update');
        }

        return redirect()->with('failed', 'Bantuan Gagal Di-Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = (new BantuanBantuanController)->delete($id);
        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            return redirect()->route('bantuan.index')->with('success', 'Bantuan Berhasil Di-Hapus');
        }

        return redirect()->with('failed', 'Bantuan Gagal Di-Hapus');
    }
}
