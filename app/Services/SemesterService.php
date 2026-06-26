<?php

namespace App\Services;

use App\Models\Semester;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SemesterService
{
    public function __construct(protected SemesterRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function activate(int $id): Model
    {
        $schoolId = session('school_id') ?? auth()->user()?->school_id;

        $this->repository->getModel()->newQuery()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->update(['is_active' => false]);

        return $this->repository->update($id, ['is_active' => true]);
    }
}
