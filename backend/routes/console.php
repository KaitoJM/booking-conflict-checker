<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:cleanup')
    ->daily()
    ->runInBackground()
    ->withoutOverlapping();
