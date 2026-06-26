<?php

namespace App\Services;

use App\Repositories\Contracts\GuardianRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class GuardianService
{
    public function __construct(protected GuardianRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with('students')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->getModel()->newQuery()->with('students')->findOrFail($id);
    }

    public function create(array $data, array $studentIds = []): Model
    {
        $guardian = $this->repository->create($data);

        if ($studentIds) {
            $guardian->students()->sync($studentIds);
        }

        return $guardian;
    }

    public function update(int $id, array $data, array $studentIds = []): Model
    {
        $guardian = $this->repository->update($id, $data);

        if ($studentIds) {
            $guardian->students()->sync($studentIds);
        }

        return $guardian->fresh('students');
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
