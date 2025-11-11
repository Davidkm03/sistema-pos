<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\BusinessSetting;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// WhatsApp Daily Report Scheduler
// Runs every minute and checks if it should send the report
Schedule::call(function () {
    $settings = BusinessSetting::first();
    
    if (!$settings || !$settings->whatsapp_daily_report_enabled) {
        return;
    }
    
    // Get configured time (e.g., "19:00:00")
    $reportTime = $settings->whatsapp_report_time ?? '19:00:00';
    $currentTime = now('America/Bogota')->format('H:i');
    $configuredTime = substr($reportTime, 0, 5); // "19:00"
    
    // Check if current minute matches configured time
    if ($currentTime === $configuredTime) {
        Artisan::call('whatsapp:daily-report');
    }
})->everyMinute()
  ->timezone('America/Bogota')
  ->name('whatsapp-daily-report-check')
  ->onOneServer();


