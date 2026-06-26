<?php

namespace App\Services;

use App\Models\SchoolSetting;
use App\Repositories\Contracts\SchoolSettingRepositoryInterface;
use Illuminate\Support\Collection;

class SchoolSettingService
{
    public function __construct(protected SchoolSettingRepositoryInterface $repository) {}

    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function getProfile(): array
    {
        $settings = SchoolSetting::query()->pluck('value', 'key');

        return $settings->map(fn ($v) => is_string($v) ? json_decode($v, true) ?? $v : $v)->toArray();
    }

    public function updateProfile(array $settings): void
    {
        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => ['value' => $value]]
            );
        }
    }
}
