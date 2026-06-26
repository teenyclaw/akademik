<?php

namespace App\Repositories\Eloquent;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }
}
