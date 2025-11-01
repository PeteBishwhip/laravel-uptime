<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckAllMonitors;
use App\Models\Tenant;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monitoring checks every minute for all tenants
Schedule::call(function () {
    // Check monitors for central/non-tenant monitors if any
    CheckAllMonitors::dispatch();
    
    // For each tenant, check their monitors
    Tenant::all()->each(function ($tenant) {
        $tenant->run(function () use ($tenant) {
            CheckAllMonitors::dispatch($tenant);
        });
    });
})->everyMinute()->name('check-all-monitors');
