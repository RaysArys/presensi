<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Presensi yang jujur, kerja yang tenang.');
})->purpose('Display an inspiring quote');
