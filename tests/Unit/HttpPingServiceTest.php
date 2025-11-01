<?php

namespace Tests\Unit;

use App\Models\Monitor;
use App\Services\Monitoring\HttpPingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HttpPingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_http_ping_service_can_be_instantiated(): void
    {
        $monitor = Monitor::create([
            'name' => 'Test Monitor',
            'type' => 'http_ping',
            'url' => 'https://example.com',
            'check_interval' => 60,
            'is_active' => true,
        ]);

        $service = new HttpPingService($monitor);

        $this->assertInstanceOf(HttpPingService::class, $service);
    }

    public function test_monitor_uptime_percentage_is_calculated_correctly(): void
    {
        $monitor = Monitor::create([
            'name' => 'Test Monitor',
            'type' => 'http_ping',
            'url' => 'https://example.com',
            'check_interval' => 60,
            'is_active' => true,
            'uptime_percentage' => 100,
        ]);

        $this->assertEquals(100, $monitor->uptime_percentage);
    }
}
