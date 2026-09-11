<?php

namespace App\Services;

use App\Models\HariLibur;
use App\Models\Ketidakhadiran;
use App\Models\Pegawai;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StatusPresensiService
{
    public function harian(Carbon $tanggal): Collection
    {
        $pegawai = Pegawai::with('jabatan')->where('status_pegawai','aktif')->orderBy('nama')->get();
        $presensi = Presensi::whereDate('tanggal',$tanggal)->get()->keyBy('pegawai_id');
        $pengajuan = Ketidakhadiran::where('status_pengajuan','disetujui')->whereDate('tanggal_mulai','<=',$tanggal)->whereDate('tanggal_selesai','>=',$tanggal)->get()->keyBy('pegawai_id');
        $libur = HariLibur::whereDate('tanggal',$tanggal)->first();

        return $pegawai->map(function ($p) use ($tanggal,$presensi,$pengajuan,$libur) {
            $hadir = $presensi->get($p->id); $izin = $pengajuan->get($p->id);
            if ($hadir) $status = $hadir->status;
            elseif ($izin) $status = $izin->jenis;
            elseif ($libur || $tanggal->isWeekend()) $status = 'libur';
            elseif ($tanggal->isFuture() || ($tanggal->isToday() && now()->format('H:i:s') < '17:00:00')) $status = 'belum presensi';
            else $status = 'alpa';
            return (object)['pegawai'=>$p,'presensi'=>$hadir,'status'=>$status,'tanggal'=>$tanggal];
        });
    }
}
