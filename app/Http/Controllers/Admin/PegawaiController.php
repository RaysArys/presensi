<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request) { $pegawai=Pegawai::with(['jabatan','user'])->when($request->q,fn($q,$v)=>$q->where(fn($x)=>$x->where('nama','like',"%$v%")->orWhere('nomor_pegawai','like',"%$v%")))->latest()->paginate(10)->withQueryString(); return view('admin.pegawai.index',compact('pegawai')); }
    public function create() { $jabatan=Jabatan::orderBy('nama_jabatan')->get(); return view('admin.pegawai.form',compact('jabatan')); }
    public function store(Request $request) { $data=$this->validateData($request); DB::transaction(function()use($data){$p=Pegawai::create($this->pegawaiData($data)); User::create(['pegawai_id'=>$p->id,'username'=>$data['username'],'password'=>$data['password'],'role'=>'staff','status_akun'=>'aktif']);}); return redirect()->route('admin.pegawai.index')->with('success','Karyawan berhasil ditambahkan.'); }
    public function edit(Pegawai $pegawai) { $pegawai->load('user'); $jabatan=Jabatan::orderBy('nama_jabatan')->get(); return view('admin.pegawai.form',compact('pegawai','jabatan')); }
    public function update(Request $request,Pegawai $pegawai) { $data=$this->validateData($request,$pegawai); DB::transaction(function()use($data,$pegawai){$pegawai->update($this->pegawaiData($data)); $u=$pegawai->user; $u->username=$data['username']; $u->status_akun=$data['status_pegawai']; if(!empty($data['password']))$u->password=$data['password']; $u->save();}); return redirect()->route('admin.pegawai.index')->with('success','Data karyawan berhasil diperbarui.'); }
    public function destroy(Pegawai $pegawai) { $pegawai->update(['status_pegawai'=>'nonaktif']); $pegawai->user?->update(['status_akun'=>'nonaktif']); return back()->with('success','Karyawan berhasil dinonaktifkan.'); }
    private function validateData(Request $r,?Pegawai $p=null):array { return $r->validate(['nomor_pegawai'=>['required','max:50',Rule::unique('pegawai')->ignore($p)],'nama'=>'required|max:100','jabatan_id'=>'nullable|exists:jabatan,id','jenis_kelamin'=>'required|in:L,P','email'=>['nullable','email',Rule::unique('pegawai')->ignore($p)],'no_handphone'=>'nullable|max:20','alamat'=>'nullable','username'=>['required','max:50',Rule::unique('users')->ignore($p?->user?->id)],'password'=>[$p?'nullable':'required','nullable','min:6'],'status_pegawai'=>'nullable|in:aktif,nonaktif']); }
    private function pegawaiData(array $d):array { return collect($d)->only(['nomor_pegawai','nama','jabatan_id','jenis_kelamin','email','no_handphone','alamat','status_pegawai'])->filter(fn($v)=>$v!==null)->all(); }
}
