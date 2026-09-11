<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id(); $table->string('nama_jabatan', 100)->unique(); $table->timestamps();
        });
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id(); $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->nullOnDelete();
            $table->string('nomor_pegawai', 50)->unique(); $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['L','P']); $table->string('email',100)->nullable()->unique();
            $table->string('no_handphone',20)->nullable(); $table->text('alamat')->nullable();
            $table->string('foto_profil')->nullable(); $table->enum('status_pegawai',['aktif','nonaktif'])->default('aktif'); $table->timestamps();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->foreignId('pegawai_id')->nullable()->unique()->constrained('pegawai')->restrictOnDelete();
            $table->string('username',50)->unique(); $table->string('password');
            $table->enum('role',['admin','supervisor','staff'])->default('staff'); $table->enum('status_akun',['aktif','nonaktif'])->default('aktif');
            $table->rememberToken(); $table->timestamps();
        });
        Schema::create('lokasi_presensi', function (Blueprint $table) {
            $table->id(); $table->string('nama_lokasi',100); $table->text('alamat');
            $table->decimal('latitude',10,8); $table->decimal('longitude',11,8); $table->unsignedInteger('radius_meter')->default(100);
            $table->string('zona_waktu',50)->default('Asia/Jakarta'); $table->time('jam_masuk')->default('08:00:00');
            $table->time('batas_terlambat')->default('08:15:00'); $table->time('jam_pulang')->default('16:00:00');
            $table->enum('status',['aktif','nonaktif'])->default('aktif'); $table->timestamps();
        });
        Schema::create('presensi', function (Blueprint $table) {
            $table->id(); $table->foreignId('pegawai_id')->constrained('pegawai')->restrictOnDelete();
            $table->foreignId('lokasi_presensi_id')->constrained('lokasi_presensi')->restrictOnDelete(); $table->date('tanggal');
            $table->time('jam_masuk')->nullable(); $table->string('foto_masuk')->nullable(); $table->decimal('latitude_masuk',10,8)->nullable();
            $table->decimal('longitude_masuk',11,8)->nullable(); $table->decimal('akurasi_masuk',8,2)->nullable(); $table->decimal('jarak_masuk',10,2)->nullable();
            $table->time('jam_pulang')->nullable(); $table->string('foto_pulang')->nullable(); $table->decimal('latitude_pulang',10,8)->nullable();
            $table->decimal('longitude_pulang',11,8)->nullable(); $table->decimal('akurasi_pulang',8,2)->nullable(); $table->decimal('jarak_pulang',10,2)->nullable();
            $table->enum('status',['hadir','terlambat'])->default('hadir'); $table->text('catatan')->nullable(); $table->timestamps();
            $table->unique(['pegawai_id','tanggal']); $table->index(['tanggal','status']);
        });
        Schema::create('ketidakhadiran', function (Blueprint $table) {
            $table->id(); $table->foreignId('pegawai_id')->constrained('pegawai')->restrictOnDelete();
            $table->enum('jenis',['izin','sakit','cuti']); $table->string('kategori_alasan',100); $table->text('alasan');
            $table->date('tanggal_mulai'); $table->date('tanggal_selesai'); $table->string('file_bukti')->nullable();
            $table->enum('status_pengajuan',['menunggu','disetujui','ditolak'])->default('menunggu'); $table->text('catatan_admin')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete(); $table->dateTime('diproses_pada')->nullable(); $table->timestamps();
            $table->index(['tanggal_mulai','tanggal_selesai']); $table->index('status_pengajuan');
        });
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->id(); $table->date('tanggal')->unique(); $table->string('nama_libur',150); $table->text('keterangan')->nullable(); $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_libur'); Schema::dropIfExists('ketidakhadiran'); Schema::dropIfExists('presensi');
        Schema::dropIfExists('lokasi_presensi'); Schema::dropIfExists('users'); Schema::dropIfExists('pegawai'); Schema::dropIfExists('jabatan');
    }
};
