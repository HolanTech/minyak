<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JualController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LabakasController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DashboardController;

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

Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});
Route::post('/proseslogin', [AuthController::class, 'proseslogin']);

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::get('/karyawan.create', [KaryawanController::class, 'create']);
    Route::post('/karyawan.store', [KaryawanController::class, 'store']);
    Route::get('/karyawan.{id}.edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan.{id}.update', [KaryawanController::class, 'update']);
    Route::delete('/karyawan.{id}.delete', [KaryawanController::class, 'destroy']);
    //edit profil
    Route::get('/editprofile', [KaryawanController::class, 'editprofile']);
    Route::post('/karyawan/{id}/updateprofile', [KaryawanController::class, 'updateprofile']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/home', [DashboardController::class, 'home']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);


    Route::get('/kas', [KasController::class, 'index']);
    Route::get('/kas.create', [KasController::class, 'create']);
    Route::post('/kas.store', [KasController::class, 'store']);
    Route::get('/kas.show{id}', [KasController::class, 'show']);
    Route::get('/kas.{id}.edit', [KasController::class, 'edit']);
    Route::put('/kas.{id}.update', [KasController::class, 'update']);
    Route::delete('/kas.{nik}.delete', [KasController::class, 'destroy']);

    Route::get('/labakas', [LabakasController::class, 'index']);
    Route::get('/labakas.create', [LabakasController::class, 'create']);
    Route::post('/labakas.store', [LabakasController::class, 'store']);
    Route::get('/labakas.show{id}', [LabakasController::class, 'show']);
    Route::get('/labakas.{id}.edit', [LabakasController::class, 'edit']);
    Route::put('/labakas.{id}.update', [LabakasController::class, 'update']);
    Route::delete('/labakas.{nik}.delete', [LabakasController::class, 'destroy']);

    Route::get('/produk', [ProdukController::class, 'index']);
    Route::get('/produk.create', [ProdukController::class, 'create']);
    Route::post('/produk.store', [ProdukController::class, 'store']);
    Route::get('/produk.show', [ProdukController::class, 'show']);
    Route::get('/produk.{id}.edit', [ProdukController::class, 'edit']);
    Route::post('/produk.{id}.update', [ProdukController::class, 'update']);
    Route::get('/produk.createstok', [ProdukController::class, 'createstok']);
    Route::post('/produk.updatestok', [ProdukController::class, 'updatestok']);
    Route::delete('/produk.{nik}.delete', [ProdukController::class, 'destroy']);

    Route::get('/jual', [JualController::class, 'index']);
    Route::get('/jual.create', [JualController::class, 'create']);
    Route::post('/jual.store', [JualController::class, 'store']);
    Route::get('/jual.show{id}', [JualController::class, 'show']);
    Route::get('/jual.{id}.edit', [JualController::class, 'edit']);
    Route::put('/jual.{id}.update', [JualController::class, 'update']);
    Route::delete('/jual.{nik}.delete', [JualController::class, 'destroy']);

    Route::get('/report', [ReportController::class, 'index']);
    Route::post('/gethistori', [ReportController::class, 'gethistori']);
});
