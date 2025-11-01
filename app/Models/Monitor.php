<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'url',
        'check_interval',
        'timeout',
        'method',
        'expected_status_codes',
        'headers',
        'body',
        'keyword',
        'keyword_present',
        'grace_period',
        'expected_heartbeat',
        'is_active',
        'status',
        'last_checked_at',
        'next_check_at',
        'last_heartbeat_at',
        'uptime_percentage',
        'average_response_time',
    ];

    protected $casts = [
        'expected_status_codes' => 'array',
        'headers' => 'array',
        'is_active' => 'boolean',
        'keyword_present' => 'boolean',
        'last_checked_at' => 'datetime',
        'next_check_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
    ];

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function statusPages(): BelongsToMany
    {
        return $this->belongsToMany(StatusPage::class, 'monitor_status_page')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('order');
    }

    public function isHttpPing(): bool
    {
        return $this->type === 'http_ping';
    }

    public function isHeartbeat(): bool
    {
        return $this->type === 'heartbeat';
    }

    public function isSslCertificate(): bool
    {
        return $this->type === 'ssl_certificate';
    }

    public function isDomainExpiration(): bool
    {
        return $this->type === 'domain_expiration';
    }
}
