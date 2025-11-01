<?php

namespace Tests\Feature;

use App\Models\Monitor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonitorTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_monitors_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/monitors');

        $response->assertStatus(200);
    }

    public function test_authenticated_users_can_create_monitor(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/monitors/create');

        $response->assertStatus(200);
    }

    public function test_monitor_can_be_created_with_valid_data(): void
    {
        $user = User::factory()->create();

        $monitorData = [
            'name' => 'Test Monitor',
            'type' => 'http_ping',
            'url' => 'https://example.com',
            'check_interval' => 60,
            'timeout' => 10,
            'method' => 'GET',
            'expected_status_codes' => '200',
            'is_active' => true,
        ];

        $this->actingAs($user);
        
        $monitor = Monitor::create($monitorData);

        $this->assertDatabaseHas('monitors', [
            'name' => 'Test Monitor',
            'url' => 'https://example.com',
            'type' => 'http_ping',
        ]);
    }

    public function test_heartbeat_monitor_can_be_created(): void
    {
        $user = User::factory()->create();

        $monitorData = [
            'name' => 'Heartbeat Monitor',
            'type' => 'heartbeat',
            'check_interval' => 300,
            'expected_heartbeat' => 3600,
            'grace_period' => 60,
            'is_active' => true,
        ];

        $this->actingAs($user);
        
        $monitor = Monitor::create($monitorData);

        $this->assertDatabaseHas('monitors', [
            'name' => 'Heartbeat Monitor',
            'type' => 'heartbeat',
            'expected_heartbeat' => 3600,
        ]);
    }

    public function test_guest_cannot_access_monitors_page(): void
    {
        $response = $this->get('/monitors');

        $response->assertRedirect('/login');
    }
}
