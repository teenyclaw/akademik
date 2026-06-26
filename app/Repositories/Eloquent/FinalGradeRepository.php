<?php

namespace App\Repositories\Eloquent;

use App\Models\FinalGrade;
use App\Repositories\Contracts\FinalGradeRepositoryInterface;

class FinalGradeRepository extends BaseRepository implements FinalGradeRepositoryInterface
{
    public function __construct(FinalGrade $model)
    {
        parent::__construct($model);
    }
}
