<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('admin.users.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.users.create', $this->formData(new User));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('role'), $request->input('role'));

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('admin.users.edit', $this->formData($this->service->findOrFail($id)));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('role'), $request->input('role'));

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    private function formData(User $item): array
    {
        return [
            'item' => $item,
            'roles' => $this->service->roles(),
        ];
    }
}
