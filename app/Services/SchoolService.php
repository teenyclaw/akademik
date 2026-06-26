<?php

namespace App\Services;

use App\Repositories\Contracts\SchoolRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SchoolService
{
    public function __construct(protected SchoolRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $logo = null): Model
    {
        $data['slug'] = Str::slug($data['name']);
        if ($logo) {
            $data['logo'] = $logo->store('schools/logos', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $logo = null): Model
    {
        if ($logo) {
            $data['logo'] = $logo->store('schools/logos', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}