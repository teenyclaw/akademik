<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(protected RoomService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('rooms.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Room;

        return view('rooms.create', compact('item'));
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('rooms.edit', compact('item'));
    }

    public function update(UpdateRoomRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dihapus.');
    }

}
