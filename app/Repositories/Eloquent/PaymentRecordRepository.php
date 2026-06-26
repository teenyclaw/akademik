<?php

namespace App\Repositories\Eloquent;

use App\Models\PaymentRecord;
use App\Repositories\Contracts\PaymentRecordRepositoryInterface;

class PaymentRecordRepository extends BaseRepository implements PaymentRecordRepositoryInterface
{
    public function __construct(PaymentRecord $model)
    {
        parent::__construct($model);
    }
}
