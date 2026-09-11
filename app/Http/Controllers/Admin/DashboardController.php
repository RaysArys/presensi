<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use App\Models\Pegawai;
use App\Services\StatusPresensiService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke(StatusPresensiService $service)
    {
        $tanggal = Carbon::today(); $data = $service->harian($tanggal);
        $ringkasan = ['karyawan'=>Pegawai::where('status_pegawai','aktif')->count(),'hadir'=>$data->where('status','hadir')->count(),'terlambat'=>$data->where('status','terlambat')->count(),'izin'=>$data->where('status','izin')->count(),'sakit'=>$data->where('status','sakit')->count(),'cuti'=>$data->where('status','cuti')->count(),'alpa'=>$data->where('status','alpa')->count()];
        $pengajuan = Ketidakhadiran::with('pegawai')->where('status_pengajuan','menunggu')->latest()->limit(6)->get();
        $chartLabels=[]; $chartHadir=[]; $chartTerlambat=[]; $chartAlpa=[];
        for($i=6;$i>=0;$i--){ $d=Carbon::today()->subDays($i); $h=$service->harian($d); $chartLabels[]=$d->translatedFormat('D'); $chartHadir[]=$h->where('status','hadir')->count(); $chartTerlambat[]=$h->where('status','terlambat')->count(); $chartAlpa[]=$h->where('status','alpa')->count(); }
        return view('admin.dashboard',compact('data','ringkasan','pengajuan','chartLabels','chartHadir','chartTerlambat','chartAlpa'));
    }
}
