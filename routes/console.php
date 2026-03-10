<?php

use App\Console\Commands\CancelExpiredPayments;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek & cancel pembayaran expired setiap menit
Schedule::command(CancelExpiredPayments::class)->everyMinute();
