<?php

namespace App\Repositories\Eloquent;

use App\Models\GradeLevel;
use App\Repositories\Contracts\GradeLevelRepositoryInterface;

class GradeLevelRepository extends BaseRepository implements GradeLevelRepositoryInterface
{
    public function __construct(GradeLevel $model)
    {
        parent::__construct($model);
    }
}
