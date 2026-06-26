<?php

namespace App\Repositories\Eloquent;

use App\Models\LessonHour;
use App\Repositories\Contracts\LessonHourRepositoryInterface;

class LessonHourRepository extends BaseRepository implements LessonHourRepositoryInterface
{
    public function __construct(LessonHour $model)
    {
        parent::__construct($model);
    }
}
