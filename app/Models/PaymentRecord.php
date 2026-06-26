<?php

namespace App\Models;

use App\Traits\BelongsToSchool;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentRecord extends Model
{
    use BelongsToSchool, HasAudit, HasFactory;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function paymentBill(): BelongsTo
    {
        return $this->belongsTo(PaymentBill::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
