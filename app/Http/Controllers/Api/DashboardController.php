<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Bantuan\Bantuan;
use App\Models\Kebutuhan\Kebutuhan;
use App\Models\Pengungsi\Pengungsi;

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
