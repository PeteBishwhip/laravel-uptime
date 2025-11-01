<?php

namespace App\Services\Monitoring;

use App\Models\Monitor;
use App\Models\MonitorCheck;
use App\Models\Incident;

abstract class MonitoringService
{
    protected Monitor $monitor;

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    abstract public function check(): MonitorCheck;

    protected function recordCheck(string $status, array $data = []): MonitorCheck
    {
        $check = MonitorCheck::create([
            'monitor_id' => $this->monitor->id,
            'status' => $status,
            'checked_at' => now(),
            ...$data,
        ]);

        $this->updateMonitorStatus($status, $data);
        $this->handleIncidents($status);

        return $check;
    }

    protected function updateMonitorStatus(string $status, array $data = []): void
    {
        $updates = [
            'status' => $status,
            'last_checked_at' => now(),
            'next_check_at' => now()->addSeconds($this->monitor->check_interval),
        ];

        if (isset($data['response_time'])) {
            $updates['average_response_time'] = $this->calculateAverageResponseTime($data['response_time']);
        }

        $updates['uptime_percentage'] = $this->calculateUptimePercentage();

        $this->monitor->update($updates);
    }

    protected function handleIncidents(string $status): void
    {
        $ongoingIncident = $this->monitor->incidents()
            ->where('status', 'ongoing')
            ->latest()
            ->first();

        if ($status === 'down') {
            if (!$ongoingIncident) {
                Incident::create([
                    'monitor_id' => $this->monitor->id,
                    'status' => 'ongoing',
                    'started_at' => now(),
                    'description' => 'Monitor went down',
                ]);
            }
        } elseif ($status === 'up' && $ongoingIncident) {
            $ongoingIncident->resolve();
        }
    }

    protected function calculateAverageResponseTime(int $newResponseTime): int
    {
        $recentChecks = $this->monitor->checks()
            ->where('response_time', '>', 0)
            ->latest()
            ->limit(100)
            ->pluck('response_time');

        $recentChecks->push($newResponseTime);

        return (int) $recentChecks->average();
    }

    protected function calculateUptimePercentage(): int
    {
        $recentChecks = $this->monitor->checks()
            ->where('checked_at', '>=', now()->subDays(30))
            ->get();

        if ($recentChecks->isEmpty()) {
            return 100;
        }

        $upChecks = $recentChecks->where('status', 'up')->count();
        $totalChecks = $recentChecks->count();

        return (int) (($upChecks / $totalChecks) * 100);
    }
}
