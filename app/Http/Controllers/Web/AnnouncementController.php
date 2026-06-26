<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Announcement\StoreAnnouncementRequest;
use App\Http\Requests\Announcement\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(protected AnnouncementService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('announcements.index', compact('items'));
    }

    public function create(): View
    {
        $item = new Announcement;

        return view('announcements.create', compact('item'));
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('announcements.edit', compact('item'));
    }

    public function update(UpdateAnnouncementRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

}
