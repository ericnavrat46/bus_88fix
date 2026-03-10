<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'capacity',
        'plate_number',
        'image',
        'facilities',
        'status',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function getFacilitiesArrayAttribute(): array
    {
        return $this->facilities ? explode(',', $this->facilities) : [];
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
