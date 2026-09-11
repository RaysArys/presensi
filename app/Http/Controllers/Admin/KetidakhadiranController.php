<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;

class KetidakhadiranController extends Controller
{
    public function index(Request $r) { $pengajuan=Ketidakhadiran::with('pegawai')->when($r->status,fn($q,$v)=>$q->where('status_pengajuan',$v))->latest()->paginate(10)->withQueryString(); return view('admin.ketidakhadiran.index',compact('pengajuan')); }
    public function update(Request $r,Ketidakhadiran $ketidakhadiran) { $d=$r->validate(['status_pengajuan'=>'required|in:disetujui,ditolak','catatan_admin'=>'nullable|max:1000']); $ketidakhadiran->update($d+['diproses_oleh'=>auth()->id(),'diproses_pada'=>now()]); return back()->with('success','Pengajuan berhasil diproses.'); }
}
