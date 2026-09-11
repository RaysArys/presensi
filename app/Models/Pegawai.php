<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $fillable = ['jabatan_id','nomor_pegawai','nama','jenis_kelamin','email','no_handphone','alamat','foto_profil','status_pegawai'];
    public function jabatan(): BelongsTo { return $this->belongsTo(Jabatan::class); }
    public function user(): HasOne { return $this->hasOne(User::class); }
    public function presensi(): HasMany { return $this->hasMany(Presensi::class); }
    public function ketidakhadiran(): HasMany { return $this->hasMany(Ketidakhadiran::class); }
}
