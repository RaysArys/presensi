<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use App\Models\LokasiPresensi;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        $d=$request->validate(['aksi'=>'required|in:masuk,pulang','latitude'=>'required|numeric|between:-90,90','longitude'=>'required|numeric|between:-180,180','akurasi'=>'required|numeric|min:0|max:500','foto'=>'required|string']);
        $pegawai=auth()->user()->pegawai; $lokasi=LokasiPresensi::where('status','aktif')->firstOrFail();
        $jarak=$this->distance((float)$d['latitude'],(float)$d['longitude'],(float)$lokasi->latitude,(float)$lokasi->longitude);
        if($jarak>$lokasi->radius_meter) throw ValidationException::withMessages(['lokasi'=>"Anda berada ".round($jarak)." meter dari lokasi. Batas maksimal {$lokasi->radius_meter} meter."]);
        if((float)$d['akurasi']>1000) throw ValidationException::withMessages(['lokasi'=>'Akurasi GPS terlalu rendah. Coba pindah ke area terbuka.']);
        $absen=Ketidakhadiran::where('pegawai_id',$pegawai->id)->where('status_pengajuan','disetujui')->whereDate('tanggal_mulai','<=',today())->whereDate('tanggal_selesai','>=',today())->exists();
        if($absen) return back()->withErrors(['presensi'=>'Anda memiliki izin/cuti/sakit yang disetujui hari ini.']);
        $foto=$this->saveBase64($d['foto'],$pegawai->id,$d['aksi']); $now=now();
        if($d['aksi']==='masuk') {
            if(Presensi::where('pegawai_id',$pegawai->id)->whereDate('tanggal',today())->exists()) return back()->withErrors(['presensi'=>'Anda sudah melakukan presensi masuk hari ini.']);
            Presensi::create(['pegawai_id'=>$pegawai->id,'lokasi_presensi_id'=>$lokasi->id,'tanggal'=>today(),'jam_masuk'=>$now->format('H:i:s'),'foto_masuk'=>$foto,'latitude_masuk'=>$d['latitude'],'longitude_masuk'=>$d['longitude'],'akurasi_masuk'=>$d['akurasi'],'jarak_masuk'=>$jarak,'status'=>$now->format('H:i:s')>$lokasi->batas_terlambat?'terlambat':'hadir']);
            return back()->with('success','Presensi masuk berhasil disimpan.');
        }
        $presensi=Presensi::where('pegawai_id',$pegawai->id)->whereDate('tanggal',today())->first();
        if(!$presensi) return back()->withErrors(['presensi'=>'Lakukan presensi masuk terlebih dahulu.']);
        if($presensi->jam_pulang) return back()->withErrors(['presensi'=>'Anda sudah melakukan presensi pulang.']);
        $presensi->update(['jam_pulang'=>$now->format('H:i:s'),'foto_pulang'=>$foto,'latitude_pulang'=>$d['latitude'],'longitude_pulang'=>$d['longitude'],'akurasi_pulang'=>$d['akurasi'],'jarak_pulang'=>$jarak]);
        return back()->with('success','Presensi pulang berhasil disimpan.');
    }
    private function distance(float $lat1,float $lon1,float $lat2,float $lon2):float { $earth=6371000; $p1=deg2rad($lat1); $p2=deg2rad($lat2); $dp=deg2rad($lat2-$lat1); $dl=deg2rad($lon2-$lon1); $a=sin($dp/2)**2+cos($p1)*cos($p2)*sin($dl/2)**2; return $earth*2*atan2(sqrt($a),sqrt(1-$a)); }
    private function saveBase64(string $data,int $pegawaiId,string $aksi):string { if(!preg_match('/^data:image\/(jpeg|jpg|png);base64,/', $data,$m)) throw ValidationException::withMessages(['foto'=>'Format foto tidak valid.']); $bytes=base64_decode(substr($data,strpos($data,',')+1),true); if($bytes===false||strlen($bytes)>5*1024*1024) throw ValidationException::withMessages(['foto'=>'Foto tidak valid atau terlalu besar.']); $ext=$m[1]==='png'?'png':'jpg'; $path='presensi/'.date('Y/m')."/{$pegawaiId}-".date('Ymd-His')."-{$aksi}.{$ext}"; Storage::disk('public')->put($path,$bytes); return $path; }
}
