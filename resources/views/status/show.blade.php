<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $statusPage->name }} - Status</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 antialiased">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($statusPage->logo_url)
                                <img src="{{ $statusPage->logo_url }}" alt="{{ $statusPage->name }}" class="h-10 w-auto">
                            @endif
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $statusPage->name }}
                            </h1>
                        </div>
                        <div class="flex items-center space-x-2">
                            @php
                                $allUp = $monitors->every(fn($m) => $m->status === 'up');
                                $anyDown = $monitors->contains(fn($m) => $m->status === 'down');
                            @endphp
                            @if($allUp)
                                <div class="flex items-center text-green-600 dark:text-green-400">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium">All Systems Operational</span>
                                </div>
                            @elseif($anyDown)
                                <div class="flex items-center text-red-600 dark:text-red-400">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Some Systems Down</span>
                                </div>
                            @else
                                <div class="flex items-center text-yellow-600 dark:text-yellow-400">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Monitoring Status</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($statusPage->description)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $statusPage->description }}
                        </p>
                    @endif
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Monitors -->
                <div class="px-4 sm:px-0 space-y-4">
                    @forelse($monitors as $monitor)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $monitor->name }}
                                        </h3>
                                        @if($monitor->url)
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $monitor->url }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        @if($statusPage->show_uptime_percentage)
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                                    {{ $monitor->uptime_percentage }}%
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Uptime (30d)
                                                </p>
                                            </div>
                                        @endif
                                        <div class="flex items-center">
                                            @if($monitor->status === 'up')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Operational
                                                </span>
                                            @elseif($monitor->status === 'down')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Down
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                    <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Unknown
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Uptime Chart (sparkline) -->
                                <div class="mt-4">
                                    <div class="flex items-end space-x-1 h-12">
                                        @php
                                            $checks = $monitor->checks->take(90)->reverse();
                                        @endphp
                                        @forelse($checks as $check)
                                            <div class="flex-1 rounded-t" 
                                                 style="height: {{ $check->status === 'up' ? '100%' : '30%' }}; background-color: {{ $check->status === 'up' ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)' }};"
                                                 title="{{ $check->checked_at->format('M d, H:i') }} - {{ ucfirst($check->status) }}">
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No check history available</p>
                                        @endforelse
                                    </div>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">90 days ago</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Today</span>
                                    </div>
                                </div>

                                @if($monitor->last_checked_at)
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Last checked {{ $monitor->last_checked_at->diffForHumans() }}
                                        @if($monitor->average_response_time > 0)
                                            • Avg response: {{ $monitor->average_response_time }}ms
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                            <div class="p-6 text-center">
                                <p class="text-gray-500 dark:text-gray-400">No monitors configured for this status page.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Recent Incidents -->
                @if($statusPage->show_incident_history)
                    <div class="mt-8 px-4 sm:px-0">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Recent Incidents</h2>
                        <div class="space-y-4">
                            @php
                                $recentIncidents = $monitors->flatMap->incidents->sortByDesc('started_at')->take(10);
                            @endphp
                            @forelse($recentIncidents as $incident)
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                    {{ $incident->monitor->name }}
                                                </h3>
                                                @if($incident->description)
                                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $incident->description }}
                                                    </p>
                                                @endif
                                                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                    <p>Started: {{ $incident->started_at->format('M d, Y H:i') }}</p>
                                                    @if($incident->resolved_at)
                                                        <p>Resolved: {{ $incident->resolved_at->format('M d, Y H:i') }}</p>
                                                        <p>Duration: {{ gmdate('H:i:s', $incident->duration) }}</p>
                                                    @else
                                                        <p class="text-red-600 dark:text-red-400">Ongoing</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                @if($incident->status === 'ongoing')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Ongoing
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Resolved
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                                    <div class="p-6 text-center">
                                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No incidents</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            No incidents have been recorded in the past 7 days.
                                        </p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 shadow mt-12">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                        Powered by Laravel Uptime Monitor
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>
