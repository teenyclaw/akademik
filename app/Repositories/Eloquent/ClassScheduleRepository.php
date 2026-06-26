<?php

namespace App\Repositories\Eloquent;

use App\Models\ClassSchedule;
use App\Repositories\Contracts\ClassScheduleRepositoryInterface;

class ClassScheduleRepository extends BaseRepository implements ClassScheduleRepositoryInterface
{
    public function __construct(ClassSchedule $model)
    {
        parent::__construct($model);
    }
}
