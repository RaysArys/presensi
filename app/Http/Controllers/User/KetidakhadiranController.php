<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;

class KetidakhadiranController extends Controller
{
    public function index() { $pengajuan=Ketidakhadiran::where('pegawai_id',auth()->user()->pegawai_id)->latest()->paginate(10); return view('user.ketidakhadiran.index',compact('pengajuan')); }
    public function store(Request $r) { $d=$r->validate(['jenis'=>'required|in:izin,sakit,cuti','kategori_alasan'=>'required|max:100','alasan'=>'required|max:1500','tanggal_mulai'=>'required|date|after_or_equal:today','tanggal_selesai'=>'required|date|after_or_equal:tanggal_mulai','file_bukti'=>'nullable|required_if:jenis,sakit|file|mimes:jpg,jpeg,png,pdf|max:3072']); if($r->hasFile('file_bukti'))$d['file_bukti']=$r->file('file_bukti')->store('bukti-sakit','public'); $d['pegawai_id']=auth()->user()->pegawai_id; Ketidakhadiran::create($d); return back()->with('success','Pengajuan berhasil dikirim dan menunggu persetujuan Admin.'); }
}
