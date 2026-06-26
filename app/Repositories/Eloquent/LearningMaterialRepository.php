<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningMaterial;
use App\Repositories\Contracts\LearningMaterialRepositoryInterface;

class LearningMaterialRepository extends BaseRepository implements LearningMaterialRepositoryInterface
{
    public function __construct(LearningMaterial $model)
    {
        parent::__construct($model);
    }
}
