<?php

namespace App\Repositories\Eloquent;

use App\Models\PaymentBill;
use App\Repositories\Contracts\PaymentBillRepositoryInterface;

class PaymentBillRepository extends BaseRepository implements PaymentBillRepositoryInterface
{
    public function __construct(PaymentBill $model)
    {
        parent::__construct($model);
    }
}
