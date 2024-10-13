<?php

use App\Http\Controllers\Api\Posko\PoskoController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\Donatur\DonaturController;
use App\Http\Controllers\Api\Kebutuhan\KebutuhanController;
use App\Http\Controllers\Api\Kecamatan\BarangController;
use App\Http\Controllers\Api\Kecamatan\JenisBarangController;
use App\Http\Controllers\Api\Kelompok\KelompokController;
use App\Http\Controllers\Api\Penduduk\PendudukController;
use App\Http\Controllers\Api\Pengungsi\PengungsiController;
use App\Http\Controllers\Api\Bantuan\BantuanController;
use App\Http\Controllers\Api\UserManagement\UserManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(['success' => true, 'message' => 'Welcome to Service'], 200);
});

Route::post('authenticate', [\App\Http\Controllers\Api\AuthController::class, 'authenticate']);
Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::post('user', function (Request $request) {
        return $request->user();
    });

    Route::controller(UserManagementController::class)
    ->prefix('user-management')
    ->group(function () {
        Route::get('index', 'index');
        Route::get('show/{id}', 'show');
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
    });

    Route::controller(PoskoController::class)
        ->prefix('posko')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::put('update/{id}', 'update');
        });

    Route::controller(PengungsiController::class)
        ->prefix('pengungsi')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::put('update/{id}', 'update');
        });

    Route::controller(KebutuhanController::class)
        ->prefix('kebutuhan')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::put('qtyReceived/{id}', 'qtyReceived');
            Route::put('update/{id}', 'update');

        });

    Route::controller(BarangController::class)
        ->prefix('barang')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::controller(JenisBarangController::class)
        ->prefix('jenis-barang')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::controller(KelompokController::class)
        ->prefix('kelompok')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::controller(PendudukController::class)
        ->prefix('penduduk')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::controller(DonaturController::class)
        ->prefix('donatur')
        ->group(function () {
            Route::get('/', 'index');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::controller(BantuanController::class)
        ->prefix('bantuan')
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/createOrEdit', 'createOrEdit');
            Route::post('store', 'store');
            Route::get('show/{id}', 'show');
            Route::put('update/{id}', 'update');
        });

    Route::get('/dashboard', [DashboardController::class, 'index']);
});
