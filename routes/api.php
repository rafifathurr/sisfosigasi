<?php

use App\Http\Controllers\Api\Pengguna\PenggunaController;
use App\Http\Controllers\Api\Posko\PoskoController;
use App\Http\Controllers\Bantuan\BantuanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Donatur\DonaturController;
use App\Http\Controllers\Kebutuhan\KebutuhanController;
use App\Http\Controllers\Kecamatan\BarangController;
use App\Http\Controllers\Kecamatan\JenisBarangController;
use App\Http\Controllers\Kelompok\KelompokController;
use App\Http\Controllers\Penduduk\PendudukController;
use App\Http\Controllers\Pengungsi\PengungsiController;
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
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);

// Route::middleware('auth:api')->post('user', function (Request $request) {
//     Route::group(['controller' => PoskoController::class, 'prefix' => 'posko'], function () {
//         Route::get('index', 'index');
//         Route::get('show', 'show');
//         Route::get('list-product', 'listProduct');
//         Route::post('store', 'store');
//         Route::get('regencies', 'regencies');
//     });
//     return $request->user();

// });
Route::middleware('auth:api')->group(function () {
    Route::post('user', function (Request $request) {
        return $request->user();
    });

    Route::controller(PoskoController::class)
        ->prefix('posko')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::get('create', 'create');
            Route::get('edit/{id}', 'edit');
            Route::put('update/{id}', 'update');
        });

    Route::controller(PenggunaController::class)
        ->prefix('pengguna')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show', 'show');
            Route::post('store', 'store');
        });

    Route::controller(PengungsiController::class)
        ->prefix('pengungsi')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::get('create', 'create');
            Route::get('edit/{id}', 'edit');
            Route::put('update/{id}', 'update');
        });

    Route::controller(KebutuhanController::class)
        ->prefix('kebutuhan')
        ->group(function () {
            Route::get('index', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::get('create', 'create');
            Route::get('qtyReceived', 'qtyReceived');
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
