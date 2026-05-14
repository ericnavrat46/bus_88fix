<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'image',
        'price_display',
        'duration_display',
        'class_display',
        'badge_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
