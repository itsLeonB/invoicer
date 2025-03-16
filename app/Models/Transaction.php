<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends FilamentModel
{
    protected $casts = [
        'amount' => MoneyCast::class,
    ];

    public function products(): HasMany
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
