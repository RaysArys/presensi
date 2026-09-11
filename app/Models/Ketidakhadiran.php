<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ketidakhadiran extends Model
{
    protected $table = 'ketidakhadiran';
    protected $guarded = [];
    protected function casts(): array { return ['tanggal_mulai'=>'date','tanggal_selesai'=>'date','diproses_pada'=>'datetime']; }
    public function pegawai(): BelongsTo { return $this->belongsTo(Pegawai::class); }
    public function admin(): BelongsTo { return $this->belongsTo(User::class, 'diproses_oleh'); }
}
