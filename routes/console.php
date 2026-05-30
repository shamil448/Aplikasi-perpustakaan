<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
|
| Reminder WA jatuh tempo H-1
|
*/

Schedule::command('app:kirim-reminder-jatuh-tempo')
    ->dailyAt('08:00');
