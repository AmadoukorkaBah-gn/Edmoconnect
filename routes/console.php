<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('wifizone:check-expirations')->everyMinute();
Schedule::command('wifizone:send-reminders')->everyMinute();
Schedule::command('abonnements:expire')->everyMinute();
Schedule::command('wifizone:retry-sync')->everyFiveMinutes();


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
