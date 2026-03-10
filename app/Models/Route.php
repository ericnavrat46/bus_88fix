<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'distance',
        'duration',
        'base_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
        ];
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        return $hours > 0 ? "{$hours}j {$minutes}m" : "{$minutes}m";
    }

    public function getRouteLabelAttribute(): string
    {
        return "{$this->origin} → {$this->destination}";
    }
}
