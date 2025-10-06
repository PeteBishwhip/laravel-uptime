<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Services\Monitoring\HttpPingService;
use App\Services\Monitoring\HeartbeatService;
use App\Services\Monitoring\SslCertificateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batchable;

class CheckMonitor implements ShouldQueue
{
    use Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Monitor $monitor
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->monitor->is_active) {
            return;
        }

        $service = match ($this->monitor->type) {
            'http_ping' => new HttpPingService($this->monitor),
            'heartbeat' => new HeartbeatService($this->monitor),
            'ssl_certificate' => new SslCertificateService($this->monitor),
            default => null,
        };

        if ($service) {
            $service->check();
        }
    }
}

