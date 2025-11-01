<?php

namespace App\Jobs;

use App\Models\Monitor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Stancl\Tenancy\Contracts\Tenant;

class CheckAllMonitors implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ?Tenant $tenant = null
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $monitorsQuery = Monitor::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('next_check_at')
                    ->orWhere('next_check_at', '<=', now());
            });

        $monitorsQuery->chunk(100, function ($monitors) {
            foreach ($monitors as $monitor) {
                CheckMonitor::dispatch($monitor);
            }
        });
    }
}

