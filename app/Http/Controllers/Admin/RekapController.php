<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StatusPresensiService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request,StatusPresensiService $service) { [$tanggal,$periode,$mulai,$selesai]=$this->range($request); $data=collect(); for($d=$mulai->copy();$d->lte($selesai);$d->addDay())$data=$data->concat($service->harian($d->copy())); if($request->status)$data=$data->where('status',$request->status); return view('admin.rekap.index',compact('data','tanggal','periode','mulai','selesai')); }
    public function export(Request $request,StatusPresensiService $service) { [$tanggal,$periode,$mulai,$selesai]=$this->range($request); $data=collect(); for($d=$mulai->copy();$d->lte($selesai);$d->addDay())$data=$data->concat($service->harian($d->copy())); return response()->streamDownload(function()use($data){$f=fopen('php://output','w'); fputcsv($f,['Nomor Pegawai','Nama','Jabatan','Tanggal','Status','Jam Masuk','Jam Pulang']); foreach($data as $r)fputcsv($f,[$r->pegawai->nomor_pegawai,$r->pegawai->nama,$r->pegawai->jabatan?->nama_jabatan,$r->tanggal->format('Y-m-d'),$r->status,$r->presensi?->jam_masuk,$r->presensi?->jam_pulang]); fclose($f);},"rekap-{$periode}-".$tanggal->format('Y-m-d').'.csv',['Content-Type'=>'text/csv']); }
    private function range(Request $request):array { $tanggal=Carbon::parse($request->input('tanggal',today()->toDateString())); $periode=$request->input('periode','harian'); if($periode==='mingguan'){ $mulai=$tanggal->copy()->startOfWeek();$selesai=$tanggal->copy()->endOfWeek(); } elseif($periode==='bulanan'){ $mulai=$tanggal->copy()->startOfMonth();$selesai=$tanggal->copy()->endOfMonth(); } else { $periode='harian';$mulai=$tanggal->copy();$selesai=$tanggal->copy(); } return [$tanggal,$periode,$mulai,$selesai]; }
}
