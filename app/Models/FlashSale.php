<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'banner_image',
        'description',
        'terms_conditions',
        'target_type',
        'target_id',
        'discount_type',
        'discount_value',
        'start_time',
        'end_time',
        'quota',
        'used_quota',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Get the full URL for the banner image.
     */
    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_image) {
            return null;
        }

        return asset('storage/' . $this->banner_image);
    }

    /**
     * Get the parent target model (TourPackage, Schedule, or Rental).
     */
    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to only include active flash sales for current time.
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
                     ->where('start_time', '<=', $now)
                     ->where('end_time', '>=', $now)
                     ->whereColumn('used_quota', '<', 'quota');
    }
}
