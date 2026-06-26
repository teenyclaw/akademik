<?php

namespace App\Repositories\Eloquent;

use App\Models\Assignment;
use App\Repositories\Contracts\AssignmentRepositoryInterface;

class AssignmentRepository extends BaseRepository implements AssignmentRepositoryInterface
{
    public function __construct(Assignment $model)
    {
        parent::__construct($model);
    }
}
