<?php

namespace App\Repositories\Eloquent;

use App\Models\AssignmentSubmission;
use App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface;

class AssignmentSubmissionRepository extends BaseRepository implements AssignmentSubmissionRepositoryInterface
{
    public function __construct(AssignmentSubmission $model)
    {
        parent::__construct($model);
    }
}
