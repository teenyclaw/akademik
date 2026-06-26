<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Major\StoreMajorRequest;
use App\Http\Requests\Major\UpdateMajorRequest;
use App\Models\Major;
use App\Services\MajorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MajorController extends Controller
{
    public function __construct(protected MajorService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('majors.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Major;

        return view('majors.create', compact('item'));
    }

    public function store(StoreMajorRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('majors.edit', compact('item'));
    }

    public function update(UpdateMajorRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('majors.index')->with('success', 'Jurusan berhasil dihapus.');
    }

}
