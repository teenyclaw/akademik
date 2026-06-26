<?php

namespace App\Repositories\Eloquent;

use App\Models\ReportCard;
use App\Repositories\Contracts\ReportCardRepositoryInterface;

class ReportCardRepository extends BaseRepository implements ReportCardRepositoryInterface
{
    public function __construct(ReportCard $model)
    {
        parent::__construct($model);
    }
}
