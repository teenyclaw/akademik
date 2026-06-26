<?php

namespace App\Repositories\Eloquent;

use App\Models\Guardian;
use App\Repositories\Contracts\GuardianRepositoryInterface;

class GuardianRepository extends BaseRepository implements GuardianRepositoryInterface
{
    public function __construct(Guardian $model)
    {
        parent::__construct($model);
    }
}
