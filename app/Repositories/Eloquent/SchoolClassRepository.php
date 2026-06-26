<?php

namespace App\Repositories\Eloquent;

use App\Models\SchoolClass;
use App\Repositories\Contracts\SchoolClassRepositoryInterface;

class SchoolClassRepository extends BaseRepository implements SchoolClassRepositoryInterface
{
    public function __construct(SchoolClass $model)
    {
        parent::__construct($model);
    }
}
