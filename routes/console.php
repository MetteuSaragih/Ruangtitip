<?php

use App\Console\Commands\BookPendingBiteshipShipments;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(BookPendingBiteshipShipments::class)
    ->everyFiveMinutes()
    ->withoutOverlapping();
