<?php

namespace App\Services;

use App\Repositories\Contracts\ClassScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassScheduleService
{
    public function __construct(protected ClassScheduleRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()
            ->with(['schoolClass', 'subject', 'teacher', 'room', 'lessonHour'])
            ->paginate($perPage);
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
}
