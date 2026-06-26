<?php

namespace App\Repositories\Eloquent;

use App\Models\Major;
use App\Repositories\Contracts\MajorRepositoryInterface;

class MajorRepository extends BaseRepository implements MajorRepositoryInterface
{
    public function __construct(Major $model)
    {
        parent::__construct($model);
    }
}
