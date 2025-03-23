<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionProduct extends FilamentModel
{
    protected $casts = [
        'price' => MoneyCast::class,
        'total' => MoneyCast::class,
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
