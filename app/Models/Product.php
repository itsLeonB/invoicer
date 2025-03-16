<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends FilamentModel
{
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
