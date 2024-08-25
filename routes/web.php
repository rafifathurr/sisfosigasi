<?php

use App\Http\Controllers\Pemerintah\AgeVulnerabilityClassificationControler;
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

Route::get('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('authenticate', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

/**
 * Home Route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::group(['controller' => AgeVulnerabilityClassificationControler::class, 'prefix' => 'age-vulnerability-classification', 'as' => 'age-vulnerability-classification.'], function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'delete')->name('delete');
        Route::get('/show', 'show')->name('show');
        Route::get('/checkLastAge', 'checkLastAge')->name('checkLastAge');
    });
});
