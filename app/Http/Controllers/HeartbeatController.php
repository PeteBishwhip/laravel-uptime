<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Services\Monitoring\HeartbeatService;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    public function ping(Request $request, Monitor $monitor)
    {
        if ($monitor->type !== 'heartbeat') {
            return response()->json([
                'error' => 'This monitor is not a heartbeat monitor',
            ], 400);
        }

        if (!$monitor->is_active) {
            return response()->json([
                'error' => 'This monitor is not active',
            ], 400);
        }

        $service = new HeartbeatService($monitor);
        $service->recordHeartbeat();

        return response()->json([
            'success' => true,
            'message' => 'Heartbeat recorded successfully',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
