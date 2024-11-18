<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\BantuanController;
use App\Http\Controllers\web\BarangController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\JenisBarangController;
use App\Http\Controllers\web\KelompokController;
use App\Http\Controllers\web\PendudukController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'login'])->name('view-login');
Route::post('/auth', [AuthController::class, 'auth'])->name('function-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('function-logout');

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('view-dashboard');

// Jenis Barang routes
Route::resource('jenis-barang', JenisBarangController::class);

//Barang
Route::resource('barang',  BarangController::class);

//kelompok
Route::resource('kelompok', KelompokController::class);

//Penduduk
Route::resource('penduduk', PendudukController::class);

Route::resource('bantuan', BantuanController::class);
