<?php

namespace App\Services\Monitoring;

use App\Models\MonitorCheck;

class HeartbeatService extends MonitoringService
{
    public function check(): MonitorCheck
    {
        $expectedInterval = $this->monitor->expected_heartbeat;
        $gracePeriod = $this->monitor->grace_period;
        $lastHeartbeat = $this->monitor->last_heartbeat_at;

        if (!$lastHeartbeat) {
            return $this->recordCheck('unknown', [
                'error_message' => 'No heartbeat received yet',
            ]);
        }

        $timeSinceLastHeartbeat = now()->diffInSeconds($lastHeartbeat);
        $maxAllowedTime = $expectedInterval + $gracePeriod;

        if ($timeSinceLastHeartbeat <= $maxAllowedTime) {
            return $this->recordCheck('up', [
                'response_time' => $timeSinceLastHeartbeat,
            ]);
        }

        return $this->recordCheck('down', [
            'error_message' => "No heartbeat received for {$timeSinceLastHeartbeat} seconds (expected every {$expectedInterval} seconds with {$gracePeriod}s grace period)",
        ]);
    }

    public function recordHeartbeat(): void
    {
        $this->monitor->update([
            'last_heartbeat_at' => now(),
            'status' => 'up',
        ]);

        // Immediately check and resolve any ongoing incidents
        $this->handleIncidents('up');
    }
}
