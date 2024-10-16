<?php

use App\Http\Controllers\AbsensiController;

Route::post('/absensi/masuk', [AbsensiController::class, 'absensiMasuk']);
Route::post('/absensi/keluar', [AbsensiController::class, 'absensiKeluar']);
Route::get('/absensi/riwayat/{karyawan_id}', [AbsensiController::class, 'riwayatAbsensi']);

