<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $table = 'presensi';
    protected $guarded = [];
    protected function casts(): array { return ['tanggal'=>'date']; }
    public function pegawai(): BelongsTo { return $this->belongsTo(Pegawai::class); }
    public function lokasi(): BelongsTo { return $this->belongsTo(LokasiPresensi::class, 'lokasi_presensi_id'); }
}
