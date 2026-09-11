<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\LokasiPresensi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Jabatan::firstOrCreate(['nama_jabatan'=>'Staff']);
        Jabatan::firstOrCreate(['nama_jabatan'=>'Supervisor']);
        Jabatan::firstOrCreate(['nama_jabatan'=>'Administrasi']);

        User::updateOrCreate(['username'=>'admin'], ['password'=>Hash::make('admin123'),'role'=>'admin','status_akun'=>'aktif']);
        $pegawai = Pegawai::updateOrCreate(['nomor_pegawai'=>'PGW001'], ['jabatan_id'=>$staff->id,'nama'=>'User Demo','jenis_kelamin'=>'L','email'=>'user@demo.test','no_handphone'=>'08123456789','alamat'=>'Jakarta','status_pegawai'=>'aktif']);
        User::updateOrCreate(['username'=>'user'], ['pegawai_id'=>$pegawai->id,'password'=>Hash::make('user123'),'role'=>'staff','status_akun'=>'aktif']);

        LokasiPresensi::firstOrCreate(['nama_lokasi'=>'Kantor Utama'], ['alamat'=>'Ganti dengan alamat kantor','latitude'=>-6.20000000,'longitude'=>106.81666600,'radius_meter'=>100,'zona_waktu'=>'Asia/Jakarta','jam_masuk'=>'08:00:00','batas_terlambat'=>'08:15:00','jam_pulang'=>'16:00:00','status'=>'aktif']);
    }
}
