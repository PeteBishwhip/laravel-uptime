<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    protected $fillable = [
        'monitor_id',
        'status',
        'description',
        'started_at',
        'resolved_at',
        'duration',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function resolve(): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'duration' => now()->diffInSeconds($this->started_at),
        ]);
    }
}
