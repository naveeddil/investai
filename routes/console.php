<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('insights:create-weekly-draft')
    ->weeklyOn(1, '08:00')
    ->withoutOverlapping();

Schedule::command('newsletter:send-weekly-tips')->dailyAt('10:00')->withoutOverlapping();
Schedule::command('newsletter:send-monthly')->monthlyOn(1, '10:30')->withoutOverlapping();
