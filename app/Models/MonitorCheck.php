<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorCheck extends Model
{
    protected $fillable = [
        'monitor_id',
        'status',
        'response_time',
        'status_code',
        'error_message',
        'response_headers',
        'ssl_certificate_info',
        'checked_at',
    ];

    protected $casts = [
        'response_headers' => 'array',
        'ssl_certificate_info' => 'array',
        'checked_at' => 'datetime',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function isUp(): bool
    {
        return $this->status === 'up';
    }

    public function isDown(): bool
    {
        return $this->status === 'down';
    }
}
