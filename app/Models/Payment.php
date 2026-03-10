<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'midtrans_transaction_id',
        'midtrans_order_id',
        'amount',
        'status',
        'payment_type',
        'snap_token',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'raw_response' => 'array',
        ];
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function isSettlement(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }
}
