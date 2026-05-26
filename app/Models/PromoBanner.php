<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PromoBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
        'promo_code',
        'start_date',
        'end_date',
        'link',
        'is_active',
        'sort_order',
        'target_type',
        'discount_type',
        'discount_value',
        'min_transaction',
        'max_discount',
        'quota',
        'used_quota',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    protected $appends = ['image_url', 'status_label', 'is_expired'];

    public function getImageUrlAttribute()
    {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/promo_banners/' . $this->image);
    }

    public function getIsExpiredAttribute()
    {
        return now()->isAfter($this->end_date->endOfDay());
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) {
            return 'Tidak Aktif';
        }
        
        if ($this->is_expired) {
            return 'Kadaluarsa';
        }

        return 'Aktif';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->whereDate('start_date', '<=', now())
                     ->whereDate('end_date', '>=', now())
                     ->where(function ($q) {
                         $q->where('quota', 0)->orWhereColumn('used_quota', '<', 'quota');
                     })
                     ->orderBy('sort_order', 'asc');
    }

    public function isValidFor($type)
    {
        if (!$this->is_active || $this->is_expired) return false;
        if ($this->quota > 0 && $this->used_quota >= $this->quota) return false;
        if ($this->target_type !== 'all' && $this->target_type !== $type) return false;
        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($this->min_transaction && $amount < $this->min_transaction) {
            return 0;
        }

        $discount = 0;
        if ($this->discount_type === 'percent') {
            $discount = $amount * ($this->discount_value / 100);
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = $this->discount_value;
        }

        return min($discount, $amount);
    }
}
