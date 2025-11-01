<?php

namespace App\Livewire\Dashboard;

use App\Models\Monitor;
use App\Models\Incident;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $totalMonitors = Monitor::count();
        $activeMonitors = Monitor::where('is_active', true)->count();
        $upMonitors = Monitor::where('status', 'up')->count();
        $downMonitors = Monitor::where('status', 'down')->count();
        $ongoingIncidents = Incident::where('status', 'ongoing')->count();
        
        $averageUptime = Monitor::where('is_active', true)->avg('uptime_percentage') ?? 100;
        
        $recentMonitors = Monitor::with('checks')
            ->latest()
            ->limit(5)
            ->get();

        $recentIncidents = Incident::with('monitor')
            ->latest('started_at')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.overview', [
            'totalMonitors' => $totalMonitors,
            'activeMonitors' => $activeMonitors,
            'upMonitors' => $upMonitors,
            'downMonitors' => $downMonitors,
            'ongoingIncidents' => $ongoingIncidents,
            'averageUptime' => round($averageUptime, 2),
            'recentMonitors' => $recentMonitors,
            'recentIncidents' => $recentIncidents,
        ]);
    }
}
