<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeType extends Model
{
    use BelongsToSchool, HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_recurring' => 'boolean',
        ];
    }

    public function paymentBills(): HasMany
    {
        return $this->hasMany(PaymentBill::class);
    }
}
