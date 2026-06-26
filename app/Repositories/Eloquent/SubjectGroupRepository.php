<?php

namespace App\Repositories\Eloquent;

use App\Models\SubjectGroup;
use App\Repositories\Contracts\SubjectGroupRepositoryInterface;

class SubjectGroupRepository extends BaseRepository implements SubjectGroupRepositoryInterface
{
    public function __construct(SubjectGroup $model)
    {
        parent::__construct($model);
    }
}
