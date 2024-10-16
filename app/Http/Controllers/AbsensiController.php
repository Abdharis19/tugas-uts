<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    // Fungsi untuk absensi masuk (clock-in)
    public function absensiMasuk(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
        ]);

        // Cek apakah sudah ada absensi masuk tanpa absensi keluar
        $absensi = Absensi::where('karyawan_id', $request->karyawan_id)
                          ->whereDate('tanggal', Carbon::today())
                          ->whereNull('jam_keluar')
                          ->first();

        if ($absensi) {
            return response()->json(['pesan' => 'Sudah melakukan absensi masuk. Silakan absen keluar.'], 400);
        }

        // Buat absensi masuk
        $absensi = new Absensi();
        $absensi->karyawan_id = $request->karyawan_id;
        $absensi->tanggal = Carbon::today();
        $absensi->jam_masuk = Carbon::now()->toTimeString();
        $absensi->save();

        return response()->json(['pesan' => 'Absensi masuk berhasil', 'data' => $absensi], 201);
    }

    // Fungsi untuk absensi keluar (clock-out)
    public function absensiKeluar(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
        ]);

        // Cari absensi masuk yang belum ada absensi keluar
        $absensi = Absensi::where('karyawan_id', $request->karyawan_id)
                          ->whereDate('tanggal', Carbon::today())
                          ->whereNull('jam_keluar')
                          ->first();

        if (!$absensi) {
            return response()->json(['pesan' => 'Absensi masuk tidak ditemukan atau sudah absen keluar.'], 400);
        }

        // Update absensi keluar
        $absensi->jam_keluar = Carbon::now()->toTimeString();
        $absensi->save();

        return response()->json(['pesan' => 'Absensi keluar berhasil', 'data' => $absensi], 200);
    }

    // Fungsi untuk menampilkan riwayat absensi
    public function riwayatAbsensi($karyawan_id)
    {
        $absensis = Absensi::where('karyawan_id', $karyawan_id)->get();

        if ($absensis->isEmpty()) {
            return response()->json(['pesan' => 'Tidak ada riwayat absensi.'], 404);
        }

        return response()->json(['data' => $absensis], 200);
    }
}
