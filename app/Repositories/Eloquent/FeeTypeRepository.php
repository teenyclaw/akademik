<?php

namespace App\Repositories\Eloquent;

use App\Models\FeeType;
use App\Repositories\Contracts\FeeTypeRepositoryInterface;

class FeeTypeRepository extends BaseRepository implements FeeTypeRepositoryInterface
{
    public function __construct(FeeType $model)
    {
        parent::__construct($model);
    }
}
