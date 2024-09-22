<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Models\Bantuan\Bantuan;
use App\Models\Kebutuhan\Kebutuhan;
use App\Models\Pengungsi\Pengungsi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        try {

            $data = [];

            $data['pengungsi'] = Pengungsi::count();
            $data['kebutuhan'] = Kebutuhan::count();
            $data['bantuan'] = Bantuan::count();

            return ApiResponse::success($data);
        } catch (\Throwable $th) {

            return ApiResponse::badRequest($th->getMessage());
        }
    }
}
