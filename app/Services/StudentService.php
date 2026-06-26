<?php

namespace App\Services;

use App\Models\StudentBiodata;
use App\Repositories\Contracts\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function __construct(protected StudentRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with(['schoolClass', 'biodata'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->getModel()->newQuery()->with('biodata')->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $photo = null, array $biodata = []): Model
    {
        return DB::transaction(function () use ($data, $photo, $biodata) {
            if ($photo) {
                $data['photo'] = $photo->store('students/photos', 'public');
            }

            $student = $this->repository->create($data);

            if ($biodata) {
                $student->biodata()->create(array_merge($biodata, ['student_id' => $student->id]));
            }

            return $student->load('biodata');
        });
    }

    public function update(int $id, array $data, ?UploadedFile $photo = null, array $biodata = []): Model
    {
        return DB::transaction(function () use ($id, $data, $photo, $biodata) {
            if ($photo) {
                $data['photo'] = $photo->store('students/photos', 'public');
            }

            $student = $this->repository->update($id, $data);

            if ($biodata) {
                StudentBiodata::updateOrCreate(['student_id' => $student->id], $biodata);
            }

            return $student->fresh(['biodata']);
        });
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
