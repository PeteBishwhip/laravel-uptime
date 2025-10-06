<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class StatusPage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'domain',
        'is_public',
        'description',
        'logo_url',
        'custom_css',
        'show_uptime_percentage',
        'show_incident_history',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'show_uptime_percentage' => 'boolean',
        'show_incident_history' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($statusPage) {
            if (empty($statusPage->slug)) {
                $statusPage->slug = Str::slug($statusPage->name);
            }
        });
    }

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class, 'monitor_status_page')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('order');
    }
}
