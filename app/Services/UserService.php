<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function __construct(protected UserRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with('roles')->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->getModel()->newQuery()->with('roles')->findOrFail($id);
    }

    public function create(array $data, ?string $role = null): Model
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repository->create($data);

        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user;
    }

    public function update(int $id, array $data, ?string $role = null): Model
    {
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->repository->update($id, $data);

        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user->fresh('roles');
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function roles()
    {
        return Role::query()->orderBy('name')->get();
    }
}
