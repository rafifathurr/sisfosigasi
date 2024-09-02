<?php

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
Route::get('registrasi', [\App\Http\Controllers\AuthController::class, 'registrasi'])->name('registrasi');
Route::post('authenticate', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::post('store-user', [\App\Http\Controllers\AuthController::class, 'store'])->name('store-user');

/**
 * Home Route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::group(['controller' => \App\Http\Controllers\UserManagement\UserManagementController::class, 'prefix' => 'user-management', 'as' => 'user-management.'], function () {
        // Get Method
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('data-table', 'dataTable')->name('dataTable');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('edit/{id}', 'edit')->name('edit');

        // Post Method
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::post('destory', 'destory')->name('destory');
        // Route::get('validation/{id}', 'validation')->name('validation');
    });

    Route::group(['controller' => \App\Http\Controllers\AgeVulnerabilityClassification\AgeVulnerabilityClassificationControler::class, 'prefix' => 'age-vulnerability-classification', 'as' => 'age-vulnerability-classification.'], function () {
        // Get Method
        Route::get('/', 'index')->name('index');
        Route::get('show', 'show')->name('show');
        Route::get('checkLastAge', 'checkLastAge')->name('checkLastAge');

        // Post Method
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::post('delete', 'delete')->name('delete');
    });
});
