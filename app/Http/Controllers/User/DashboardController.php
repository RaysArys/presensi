<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use App\Models\LokasiPresensi;
use App\Models\Presensi;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $pegawai=auth()->user()->pegawai; abort_unless($pegawai,403,'Akun belum terhubung dengan data pegawai.');
        $lokasi=LokasiPresensi::where('status','aktif')->first();
        $hariIni=Presensi::where('pegawai_id',$pegawai->id)->whereDate('tanggal',today())->first();
        $riwayat=Presensi::where('pegawai_id',$pegawai->id)->latest('tanggal')->limit(7)->get();
        $pengajuanAktif=Ketidakhadiran::where('pegawai_id',$pegawai->id)->where('status_pengajuan','menunggu')->count();
        return view('user.dashboard',compact('pegawai','lokasi','hariIni','riwayat','pengajuanAktif'));
    }
}
