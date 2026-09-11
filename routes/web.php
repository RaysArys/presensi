<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KetidakhadiranController as AdminKetidakhadiran;
use App\Http\Controllers\Admin\LokasiPresensiController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\KetidakhadiranController as UserKetidakhadiran;
use App\Http\Controllers\User\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/',fn()=>auth()->check()?(auth()->user()->role==='admin'?redirect()->route('admin.dashboard'):redirect()->route('user.dashboard')):redirect()->route('login'));
Route::middleware('guest')->group(function(){ Route::get('/login',[AuthController::class,'showLogin'])->name('login'); Route::post('/login',[AuthController::class,'login'])->name('login.process'); });
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function(){
    Route::get('/dashboard',AdminDashboard::class)->name('dashboard');
    Route::resource('pegawai',PegawaiController::class)->except('show');
    Route::resource('lokasi',LokasiPresensiController::class)->except('show');
    Route::get('/presensi-rekap',[RekapController::class,'index'])->name('rekap.index');
    Route::get('/presensi-rekap/export',[RekapController::class,'export'])->name('rekap.export');
    Route::get('/ketidakhadiran',[AdminKetidakhadiran::class,'index'])->name('ketidakhadiran.index');
    Route::patch('/ketidakhadiran/{ketidakhadiran}',[AdminKetidakhadiran::class,'update'])->name('ketidakhadiran.update');
});

Route::prefix('user')->name('user.')->middleware(['auth','role:staff,supervisor'])->group(function(){
    Route::get('/dashboard',UserDashboard::class)->name('dashboard'); Route::post('/presensi',[PresensiController::class,'store'])->name('presensi.store');
    Route::get('/pengajuan',[UserKetidakhadiran::class,'index'])->name('ketidakhadiran.index'); Route::post('/pengajuan',[UserKetidakhadiran::class,'store'])->name('ketidakhadiran.store');
});
