<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnnouncementController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $announcements = Announcement::query()
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->orderByDesc('published_at')
            ->paginate(15);

        return AnnouncementResource::collection($announcements);
    }

    public function show(int $id): AnnouncementResource
    {
        $announcement = Announcement::query()->findOrFail($id);

        return new AnnouncementResource($announcement);
    }
}
