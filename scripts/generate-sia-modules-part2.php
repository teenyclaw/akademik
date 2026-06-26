<?php

/**
 * Generator part 2 - remaining SIA Akademik modules.
 */

$base = dirname(__DIR__);

function w(string $path, string $content): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $content);
    echo "Created: {$path}\n";
}

function repoPair(string $name, string $model): array
{
    w("{$GLOBALS['base']}/app/Repositories/Contracts/{$name}RepositoryInterface.php", <<<PHP
<?php

namespace App\\Repositories\\Contracts;

interface {$name}RepositoryInterface extends BaseRepositoryInterface
{
}

PHP);

    w("{$GLOBALS['base']}/app/Repositories/Eloquent/{$name}Repository.php", <<<PHP
<?php

namespace App\\Repositories\\Eloquent;

use App\\Models\\{$model};
use App\\Repositories\\Contracts\\{$name}RepositoryInterface;

class {$name}Repository extends BaseRepository implements {$name}RepositoryInterface
{
    public function __construct({$model} \$model)
    {
        parent::__construct(\$model);
    }
}

PHP);

    return ["\\App\\Repositories\\Contracts\\{$name}RepositoryInterface::class => \\App\\Repositories\\Eloquent\\{$name}Repository::class"];
}

$bindings = [];

// --- School ---
$bindings = array_merge($bindings, repoPair('School', 'School'));

w("{$base}/app/Services/SchoolService.php", <<<'PHP'
<?php

namespace App\Services;

use App\Repositories\Contracts\SchoolRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SchoolService
{
    public function __construct(protected SchoolRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data, ?UploadedFile $logo = null): Model
    {
        $data['slug'] = Str::slug($data['name']);
        if ($logo) {
            $data['logo'] = $logo->store('schools/logos', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, ?UploadedFile $logo = null): Model
    {
        if ($logo) {
            $data['logo'] = $logo->store('schools/logos', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
PHP);

w("{$base}/app/Http/Requests/School/StoreSchoolRequest.php", <<<'PHP'
<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'report_header' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }
}
PHP);

w("{$base}/app/Http/Requests/School/UpdateSchoolRequest.php", <<<'PHP'
<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        return (new StoreSchoolRequest)->rules();
    }
}
PHP);

w("{$base}/app/Http/Controllers/Web/SchoolController.php", <<<'PHP'
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreSchoolRequest;
use App\Http\Requests\School\UpdateSchoolRequest;
use App\Models\EducationLevel;
use App\Models\School;
use App\Services\SchoolService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function __construct(protected SchoolService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('schools.index', compact('items'));
    }

    public function create(): View
    {
        $item = new School;
        $educationLevels = EducationLevel::query()->orderBy('name')->get();

        return view('schools.create', compact('item', 'educationLevels'));
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $this->service->create($request->safe()->except('logo'), $request->file('logo'));

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);
        $educationLevels = EducationLevel::query()->orderBy('name')->get();

        return view('schools.edit', compact('item', 'educationLevels'));
    }

    public function update(UpdateSchoolRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->safe()->except('logo'), $request->file('logo'));

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}
PHP);

// School views
foreach (['index' => 'Sekolah', 'create' => 'Tambah Sekolah', 'edit' => 'Edit Sekolah'] as $view => $title) {
    if ($view === 'index') {
        w("{$base}/resources/views/schools/index.blade.php", <<<'BLADE'
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Sekolah</h2>
            <a href="{{ route('schools.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a>
        </div>
    </x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('partials.flash')
        <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left">Nama</th><th class="px-4 py-2 text-left">Email</th><th class="px-4 py-2 text-left">Aktif</th><th class="px-4 py-2">Aksi</th>
                </tr></thead>
                <tbody class="divide-y">@forelse($items as $item)<tr>
                    <td class="px-4 py-2">{{ $item->name }}</td>
                    <td class="px-4 py-2">{{ $item->email }}</td>
                    <td class="px-4 py-2">{{ $item->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <a href="{{ route('schools.edit', $item) }}" class="text-indigo-600">Edit</a>
                        <form action="{{ route('schools.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form>
                    </td>
                </tr>@empty<tr><td colspan="4" class="px-4 py-4 text-center">Belum ada data.</td></tr>@endforelse</tbody>
            </table>
            <div class="p-4">{{ $items->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
BLADE);
    } else {
        w("{$base}/resources/views/schools/{$view}.blade.php", <<<BLADE
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{$title}</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
        @include('partials.flash')
        <form action="{{ route('schools." . ($view === 'create' ? 'store' : 'update') . "', " . ($view === 'edit' ? '$item' : '') . ") }}" method="POST" enctype="multipart/form-data">
            @csrf @if('$view'==='edit') @method('PUT') @endif
            @include('schools._form')
            <div class="mt-4"><x-primary-button>Simpan</x-primary-button></div>
        </form>
    </div></div>
</x-app-layout>
BLADE);
    }
}

w("{$base}/resources/views/schools/_form.blade.php", <<<'BLADE'
<div class="mb-4"><x-input-label for="name" value="Nama Sekolah" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $item->name ?? '') }}" required /><x-input-error :messages="$errors->get('name')" /></div>
<div class="mb-4"><x-input-label for="education_level_id" value="Jenjang Pendidikan" /><select id="education_level_id" name="education_level_id" class="mt-1 block w-full border-gray-300 rounded-md"><option value="">- Pilih -</option>@foreach($educationLevels as $level)<option value="{{ $level->id }}" @selected(old('education_level_id', $item->education_level_id ?? '') == $level->id)>{{ $level->name }}</option>@endforeach</select></div>
<div class="mb-4"><x-input-label for="address" value="Alamat" /><textarea id="address" name="address" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('address', $item->address ?? '') }}</textarea></div>
<div class="mb-4"><x-input-label for="phone" value="Telepon" /><x-text-input id="phone" name="phone" class="mt-1 block w-full" value="{{ old('phone', $item->phone ?? '') }}" /></div>
<div class="mb-4"><x-input-label for="email" value="Email" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $item->email ?? '') }}" /></div>
<div class="mb-4"><x-input-label for="website" value="Website" /><x-text-input id="website" name="website" class="mt-1 block w-full" value="{{ old('website', $item->website ?? '') }}" /></div>
<div class="mb-4"><x-input-label for="logo" value="Logo" /><input type="file" id="logo" name="logo" class="mt-1 block w-full" accept="image/*" />@if(!empty($item->logo))<p class="text-sm text-gray-500 mt-1">Logo saat ini: {{ $item->logo }}</p>@endif</div>
<div class="mb-4"><label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true)) class="rounded border-gray-300"><span class="ml-2">Aktif</span></label></div>
BLADE);

// --- SchoolClass ---
$bindings = array_merge($bindings, repoPair('SchoolClass', 'SchoolClass'));

w("{$base}/app/Services/SchoolClassService.php", <<<'PHP'
<?php

namespace App\Services;

use App\Repositories\Contracts\SchoolClassRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SchoolClassService
{
    public function __construct(protected SchoolClassRepositoryInterface $repository) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getModel()->newQuery()->with(['academicYear', 'gradeLevel', 'major', 'homeroomTeacher'])->paginate($perPage);
    }

    public function findOrFail(int $id): Model
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
PHP);

w("{$base}/app/Http/Requests/SchoolClass/StoreSchoolClassRequest.php", <<<'PHP'
<?php

namespace App\Http\Requests\SchoolClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolClassRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'major_id' => 'nullable|exists:majors,id',
            'name' => 'required|string|max:255',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'nullable|integer|min:1',
        ];
    }
}
PHP);

w("{$base}/app/Http/Requests/SchoolClass/UpdateSchoolClassRequest.php", <<<'PHP'
<?php

namespace App\Http\Requests\SchoolClass;

class UpdateSchoolClassRequest extends StoreSchoolClassRequest {}
PHP);

w("{$base}/app/Http/Controllers/Web/SchoolClassController.php", <<<'PHP'
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolClass\StoreSchoolClassRequest;
use App\Http\Requests\SchoolClass\UpdateSchoolClassRequest;
use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\Major;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Services\SchoolClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function __construct(protected SchoolClassService $service) {}

    public function index(): View
    {
        $items = $this->service->paginate(15);

        return view('classes.index', compact('items'));
    }

    public function create(): View
    {
        return view('classes.create', $this->formData(new SchoolClass));
    }

    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $item = $this->service->findOrFail($id);

        return view('classes.edit', $this->formData($item));
    }

    public function update(UpdateSchoolClassRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }

    private function formData(SchoolClass $item): array
    {
        return [
            'item' => $item,
            'academicYears' => AcademicYear::query()->orderByDesc('start_date')->get(),
            'gradeLevels' => GradeLevel::query()->orderBy('level_number')->get(),
            'majors' => Major::query()->orderBy('name')->get(),
            'teachers' => Teacher::query()->orderBy('name')->get(),
        ];
    }
}
PHP);

w("{$base}/resources/views/classes/index.blade.php", <<<'BLADE'
<x-app-layout>
    <x-slot name="header"><div class="flex justify-between"><h2 class="font-semibold text-xl">Kelas</h2><a href="{{ route('classes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a></div></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">@include('partials.flash')
        <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto"><table class="min-w-full"><thead class="bg-gray-50"><tr>
            <th class="px-4 py-2 text-left">Nama</th><th class="px-4 py-2 text-left">Tingkat</th><th class="px-4 py-2 text-left">TA</th><th class="px-4 py-2">Aksi</th>
        </tr></thead><tbody>@forelse($items as $item)<tr class="border-t">
            <td class="px-4 py-2">{{ $item->name }}</td><td class="px-4 py-2">{{ $item->gradeLevel?->name }}</td><td class="px-4 py-2">{{ $item->academicYear?->name }}</td>
            <td class="px-4 py-2"><a href="{{ route('classes.edit', $item) }}" class="text-indigo-600">Edit</a>
            <form action="{{ route('classes.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td>
        </tr>@empty<tr><td colspan="4" class="px-4 py-4 text-center">Belum ada data.</td></tr>@endforelse</tbody></table><div class="p-4">{{ $items->links() }}</div></div>
    </div></div>
</x-app-layout>
BLADE);

w("{$base}/resources/views/classes/create.blade.php", <<<'BLADE'
<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Tambah Kelas</h2></x-slot>
<div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
<form action="{{ route('classes.store') }}" method="POST">@csrf @include('classes._form')<div class="mt-4"><x-primary-button>Simpan</x-primary-button></div></form></div></div></x-app-layout>
BLADE);

w("{$base}/resources/views/classes/edit.blade.php", <<<'BLADE'
<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Edit Kelas</h2></x-slot>
<div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
<form action="{{ route('classes.update', $item) }}" method="POST">@csrf @method('PUT') @include('classes._form')<div class="mt-4"><x-primary-button>Perbarui</x-primary-button></div></form></div></div></x-app-layout>
BLADE);

w("{$base}/resources/views/classes/_form.blade.php", <<<'BLADE'
<div class="mb-4"><x-input-label value="Tahun Ajaran" /><select name="academic_year_id" class="mt-1 block w-full border-gray-300 rounded-md" required>@foreach($academicYears as $y)<option value="{{ $y->id }}" @selected(old('academic_year_id', $item->academic_year_id ?? '') == $y->id)>{{ $y->name }}</option>@endforeach</select></div>
<div class="mb-4"><x-input-label value="Tingkat" /><select name="grade_level_id" class="mt-1 block w-full border-gray-300 rounded-md" required>@foreach($gradeLevels as $g)<option value="{{ $g->id }}" @selected(old('grade_level_id', $item->grade_level_id ?? '') == $g->id)>{{ $g->name }}</option>@endforeach</select></div>
<div class="mb-4"><x-input-label value="Jurusan" /><select name="major_id" class="mt-1 block w-full border-gray-300 rounded-md"><option value="">-</option>@foreach($majors as $m)<option value="{{ $m->id }}" @selected(old('major_id', $item->major_id ?? '') == $m->id)>{{ $m->name }}</option>@endforeach</select></div>
<div class="mb-4"><x-input-label for="name" value="Nama Kelas" /><x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name', $item->name ?? '') }}" required /></div>
<div class="mb-4"><x-input-label value="Wali Kelas" /><select name="homeroom_teacher_id" class="mt-1 block w-full border-gray-300 rounded-md"><option value="">-</option>@foreach($teachers as $t)<option value="{{ $t->id }}" @selected(old('homeroom_teacher_id', $item->homeroom_teacher_id ?? '') == $t->id)>{{ $t->name }}</option>@endforeach</select></div>
<div class="mb-4"><x-input-label for="capacity" value="Kapasitas" /><x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full" value="{{ old('capacity', $item->capacity ?? '') }}" /></div>
BLADE);

file_put_contents("{$base}/scripts/generated-bindings-part2.txt", implode("\n", $bindings));
echo "\nPart 2 partial complete.\n";
