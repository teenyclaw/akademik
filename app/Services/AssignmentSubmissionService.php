<?php

namespace App\Services;

use App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class AssignmentSubmissionService
{
    public function __construct(protected AssignmentSubmissionRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with(['assignment', 'student'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $file = null): Model
    {
        if ($file) {
            $data['file_path'] = $file->store('assignment-submissions', 'public');
        }
        $data['submitted_at'] = now();
        $data['status'] = 'submitted';

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $file = null): Model
    {
        if ($file) {
            $data['file_path'] = $file->store('assignment-submissions', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
