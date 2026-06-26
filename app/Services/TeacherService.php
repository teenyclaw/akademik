<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class TeacherService
{
    public function __construct(protected TeacherRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with('user')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $photo = null): Model
    {
        if ($photo) {
            $data['photo'] = $photo->store('teachers/photos', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $photo = null): Model
    {
        if ($photo) {
            $data['photo'] = $photo->store('teachers/photos', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
