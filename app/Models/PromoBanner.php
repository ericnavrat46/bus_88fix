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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
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
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now())
                     ->orderBy('sort_order', 'asc');
    }
}
