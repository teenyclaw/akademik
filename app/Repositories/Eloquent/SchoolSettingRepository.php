<?php

namespace App\Repositories\Eloquent;

use App\Models\SchoolSetting;
use App\Repositories\Contracts\SchoolSettingRepositoryInterface;

class SchoolSettingRepository extends BaseRepository implements SchoolSettingRepositoryInterface
{
    public function __construct(SchoolSetting $model)
    {
        parent::__construct($model);
    }
}
