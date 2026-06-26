<?php

namespace App\Repositories\Eloquent;

use App\Models\Teacher;
use App\Repositories\Contracts\TeacherRepositoryInterface;

class TeacherRepository extends BaseRepository implements TeacherRepositoryInterface
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }
}
