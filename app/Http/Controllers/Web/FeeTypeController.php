<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeeType\StoreFeeTypeRequest;
use App\Http\Requests\FeeType\UpdateFeeTypeRequest;
use App\Models\FeeType;
use App\Services\FeeTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeTypeController extends Controller
{
    public function __construct(protected FeeTypeService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('fee-types.index', compact('items'));
    }

    public function create(): View
    {
        $item = new FeeType;

        return view('fee-types.create', compact('item'));
    }

    public function store(StoreFeeTypeRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('fee-types.index')->with('success', 'Jenis Biaya berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('fee-types.edit', compact('item'));
    }

    public function update(UpdateFeeTypeRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('fee-types.index')->with('success', 'Jenis Biaya berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('fee-types.index')->with('success', 'Jenis Biaya berhasil dihapus.');
    }

}
