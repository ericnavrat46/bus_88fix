<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_days',
        'price_per_person',
        'image',
        'destinations',
        'inclusions',
        'exclusions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_person' => 'decimal:2',
            'destinations' => 'array',
            'inclusions' => 'array',
            'exclusions' => 'array',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function flashSale(): MorphOne
    {
        return $this->morphOne(FlashSale::class, 'target');
    }

    public function getActiveFlashSaleAttribute()
    {
        return $this->flashSale()->active()->first();
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function getFinalPriceAttribute()
    {
        $flash = $this->active_flash_sale;
        if (!$flash) return $this->price_per_person;

        if ($flash->discount_type === 'percentage') {
            return $this->price_per_person * (1 - ($flash->discount_value / 100));
        }

        return max(0, $this->price_per_person - $flash->discount_value);
    }
}
