<?php

namespace Tests\Feature;

use App\Models\Monitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeartbeatTest extends TestCase
{
    use RefreshDatabase;

    public function test_heartbeat_endpoint_accepts_valid_ping(): void
    {
        $monitor = Monitor::create([
            'name' => 'Test Heartbeat',
            'type' => 'heartbeat',
            'check_interval' => 300,
            'expected_heartbeat' => 3600,
            'grace_period' => 60,
            'is_active' => true,
            'status' => 'unknown',
        ]);

        $response = $this->postJson("/heartbeat/{$monitor->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $monitor->refresh();
        $this->assertNotNull($monitor->last_heartbeat_at);
        $this->assertEquals('up', $monitor->status);
    }

    public function test_heartbeat_endpoint_rejects_non_heartbeat_monitor(): void
    {
        $monitor = Monitor::create([
            'name' => 'HTTP Monitor',
            'type' => 'http_ping',
            'url' => 'https://example.com',
            'check_interval' => 60,
            'is_active' => true,
        ]);

        $response = $this->postJson("/heartbeat/{$monitor->id}");

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'This monitor is not a heartbeat monitor',
            ]);
    }

    public function test_heartbeat_endpoint_rejects_inactive_monitor(): void
    {
        $monitor = Monitor::create([
            'name' => 'Inactive Heartbeat',
            'type' => 'heartbeat',
            'check_interval' => 300,
            'expected_heartbeat' => 3600,
            'is_active' => false,
        ]);

        $response = $this->postJson("/heartbeat/{$monitor->id}");

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'This monitor is not active',
            ]);
    }
}
