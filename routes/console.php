<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\VolunteerController;

Schedule::call([VolunteerController::class, 'processNoShows'])
    ->dailyAt('00:10')
    ->timezone('Asia/Manila')
    ->name('cleanup-drive-no-show-penalties')
    ->withoutOverlapping();
Schedule::call(function () {
    \App\Models\CleanupDrive::where('status', 'scheduled')
        ->where('start_at', '<=', now())
        ->where('end_at', '>', now())
        ->update(['status' => 'ongoing']);
    \App\Models\CleanupDrive::whereIn('status', ['scheduled', 'ongoing'])
        ->where('end_at', '<=', now())
        ->update(['status' => 'completed']);
})
    ->everyMinute()
    ->timezone('Asia/Manila')
    ->name('cleanup-drive-status-sync')
    ->withoutOverlapping();
