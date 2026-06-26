<?php

namespace App\Services;

use App\Repositories\Contracts\LearningMaterialRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class LearningMaterialService
{
    public function __construct(protected LearningMaterialRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with(['subject', 'schoolClass'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $file = null): Model
    {
        if ($file) {
            $data['file_path'] = $file->store('learning-materials', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $file = null): Model
    {
        if ($file) {
            $data['file_path'] = $file->store('learning-materials', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
