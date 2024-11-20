<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\Bantuan\BantuanController;
use App\Http\Controllers\Api\Kecamatan\BarangController;
use App\Http\Controllers\Api\Penduduk\PendudukController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.check');
    }
    public function index()
    {
        $response =  (new BantuanController)->index();
        $response_body = json_decode($response->content());
        $bantuan = $response_body->data->data;

        $response =  (new PendudukController)->index();
        $response_body = json_decode($response->content());
        $penduduk = $response_body->data->data;

        $response =  (new BarangController)->index();
        $response_body = json_decode($response->content());
        $barang = $response_body->data->data;

        return view('home', compact('bantuan', 'penduduk', 'barang'));
    }
}
