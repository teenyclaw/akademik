<?php

namespace App\Repositories\Eloquent;

use App\Models\AssessmentComponent;
use App\Repositories\Contracts\AssessmentComponentRepositoryInterface;

class AssessmentComponentRepository extends BaseRepository implements AssessmentComponentRepositoryInterface
{
    public function __construct(AssessmentComponent $model)
    {
        parent::__construct($model);
    }
}
