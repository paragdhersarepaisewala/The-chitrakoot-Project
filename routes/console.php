<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tunnel', function () {
    $script = base_path('host-online.ps1');
    $this->info('Starting Cloudflare Tunnel & Local Server...');
    passthru("powershell -NoProfile -ExecutionPolicy Bypass -File \"{$script}\"");
})->purpose('Auto host on Cloudflare temp domain and copy link to clipboard');

Artisan::command('host:share', function () {
    $this->call('tunnel');
})->purpose('Alias for tunnel command');

Schedule::command('events:archive-expired')->hourly();
