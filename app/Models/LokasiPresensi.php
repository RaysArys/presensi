<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiPresensi extends Model
{
    protected $table = 'lokasi_presensi';
    protected $guarded = [];
    protected function casts(): array { return ['latitude'=>'decimal:8','longitude'=>'decimal:8']; }
}
