<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolSetting\UpdateSchoolSettingRequest;
use App\Services\SchoolSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolSettingController extends Controller
{
    public function __construct(protected SchoolSettingService $service) {}

    public function edit(): View
    {
        $settings = $this->service->getProfile();

        return view('school-settings.edit', compact('settings'));
    }

    public function update(UpdateSchoolSettingRequest $request): RedirectResponse
    {
        $this->service->updateProfile($request->validated());

        return redirect()->route('school-settings.edit')->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
