<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs daily and emails each user a reminder for goals due within 3 days or overdue.
// Make sure your server/host actually runs the Laravel scheduler (see README "Reminders in production").
Schedule::command('goals:remind')->dailyAt('08:00');
