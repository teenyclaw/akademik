<?php

/**
 * One-time generator for SIA Akademik modules.
 * Run: php scripts/generate-sia-modules.php
 */

$base = dirname(__DIR__);

function writeFile(string $path, string $content): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $content);
    echo "Created: {$path}\n";
}

function studly(string $name): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
}

function snake(string $name): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
}

function moduleRepoContract(string $name): string
{
    return <<<PHP
<?php

namespace App\\Repositories\\Contracts;

interface {$name}RepositoryInterface extends BaseRepositoryInterface
{
}

PHP;
}

function moduleRepo(string $name, string $model): string
{
    return <<<PHP
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

PHP;
}

function moduleService(string $name, string $model, bool $scoped = true): string
{
    $repo = "{$name}RepositoryInterface";
    $activate = '';

    if ($name === 'AcademicYear' || $name === 'Semester') {
        $activate = <<<'PHP'

    public function activate(int $id): Model
    {
        $schoolId = session('school_id') ?? auth()->user()?->school_id;

        $this->repository->getModel()->newQuery()
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->update(['is_active' => false]);

        return $this->repository->update($id, ['is_active' => true]);
    }
PHP;
        $activate = str_replace('Model', "\\Illuminate\\Database\\Eloquent\\Model", $activate);
    }

    return <<<PHP
<?php

namespace App\\Services;

use App\\Models\\{$model};
use App\\Repositories\\Contracts\\{$repo};
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Pagination\\LengthAwarePaginator;

class {$name}Service
{
    public function __construct(protected {$repo} \$repository) {}

    public function paginate(int \$perPage = 15): LengthAwarePaginator
    {
        return \$this->repository->paginate(\$perPage);
    }

    public function findOrFail(int \$id): Model
    {
        return \$this->repository->findOrFail(\$id);
    }

    public function create(array \$data): Model
    {
        return \$this->repository->create(\$data);
    }

    public function update(int \$id, array \$data): Model
    {
        return \$this->repository->update(\$id, \$data);
    }

    public function delete(int \$id): bool
    {
        return \$this->repository->delete(\$id);
    }
{$activate}
}

PHP;
}

function storeRequest(string $name, array $rules): string
{
    $rulesStr = var_export($rules, true);
    $rulesStr = preg_replace('/^(\s*)array \(/m', '$1return [', $rulesStr);
    $rulesStr = rtrim($rulesStr, ')').';';

    return <<<PHP
<?php

namespace App\\Http\\Requests\\{$name};

use Illuminate\\Foundation\\Http\\FormRequest;

class Store{$name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        {$rulesStr}
    }
}

PHP;
}

function updateRequest(string $name, array $rules, string $param = 'id'): string
{
    $rulesStr = var_export($rules, true);
    $rulesStr = preg_replace('/^(\s*)array \(/m', '$1return [', $rulesStr);
    $rulesStr = rtrim($rulesStr, ')').';';

    return <<<PHP
<?php

namespace App\\Http\\Requests\\{$name};

use Illuminate\\Foundation\\Http\\FormRequest;

class Update{$name}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        {$rulesStr}
    }
}

PHP;
}

function crudController(
    string $name,
    string $model,
    string $routeName,
    string $viewFolder,
    string $title,
    string $permissionPrefix,
    bool $hasActivate = false,
): string {
    $activateMethods = '';
    $activateUse = '';

    if ($hasActivate) {
        $activateMethods = <<<'PHP'

    public function activate(int $id): RedirectResponse
    {
        $this->service->activate($id);

        return redirect()->route('ROUTE.index')->with('success', 'ITEM berhasil diaktifkan.');
    }
PHP;
        $activateMethods = str_replace(['ROUTE', 'ITEM'], [$routeName, $title], $activateMethods);
    }

    return <<<PHP
<?php

namespace App\\Http\\Controllers\\Web;

use App\\Http\\Controllers\\Controller;
use App\\Http\\Requests\\{$name}\\Store{$name}Request;
use App\\Http\\Requests\\{$name}\\Update{$name}Request;
use App\\Models\\{$model};
use App\\Services\\{$name}Service;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\View\\View;

class {$name}Controller extends Controller
{
    public function __construct(protected {$name}Service \$service) {}

    public function index(): View
    {
        \$items = \$this->service->paginate(15);

        return view('{$viewFolder}.index', compact('items'));
    }

    public function create(): View
    {
        \$item = new {$model};

        return view('{$viewFolder}.create', compact('item'));
    }

    public function store(Store{$name}Request \$request): RedirectResponse
    {
        \$this->service->create(\$request->validated());

        return redirect()->route('{$routeName}.index')->with('success', '{$title} berhasil ditambahkan.');
    }

    public function edit(int \$id): View
    {
        \$item = \$this->service->findOrFail(\$id);

        return view('{$viewFolder}.edit', compact('item'));
    }

    public function update(Update{$name}Request \$request, int \$id): RedirectResponse
    {
        \$this->service->update(\$id, \$request->validated());

        return redirect()->route('{$routeName}.index')->with('success', '{$title} berhasil diperbarui.');
    }

    public function destroy(int \$id): RedirectResponse
    {
        \$this->service->delete(\$id);

        return redirect()->route('{$routeName}.index')->with('success', '{$title} berhasil dihapus.');
    }
{$activateMethods}
}

PHP;
}

function bladeIndex(string $folder, string $title, string $routeName, array $columns): string
{
    $headers = '';
    $cells = '';
    foreach ($columns as $col => $label) {
        $headers .= "                        <th class=\"px-4 py-2 text-left\">{$label}</th>\n";
        $cells .= "                            <td class=\"px-4 py-2\">{{ \$item->{$col} }}</td>\n";
    }

    return <<<BLADE
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{$title}</h2>
            @can('{$routeName}.create')
            <a href="{{ route('{$routeName}.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
{$headers}                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse(\$items as \$item)
                        <tr>
{$cells}                            <td class="px-4 py-2 space-x-2">
                                @can('{$routeName}.edit')
                                <a href="{{ route('{$routeName}.edit', \$item) }}" class="text-indigo-600">Edit</a>
                                @endcan
                                @can('{$routeName}.delete')
                                <form action="{{ route('{$routeName}.destroy', \$item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="99" class="px-4 py-4 text-center text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ \$items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
BLADE;
}

function bladeCreate(string $folder, string $title, string $routeName): string
{
    return <<<BLADE
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Tambah {$title}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
            @include('partials.flash')
            <form action="{{ route('{$routeName}.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('{$folder}._form')
                <div class="mt-4"><x-primary-button>Simpan</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>
BLADE;
}

function bladeEdit(string $folder, string $title, string $routeName): string
{
    return <<<BLADE
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit {$title}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
            @include('partials.flash')
            <form action="{{ route('{$routeName}.update', \$item) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('{$folder}._form')
                <div class="mt-4"><x-primary-button>Perbarui</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>
BLADE;
}

// Standard CRUD modules
$modules = [
    ['name' => 'GradeLevel', 'model' => 'GradeLevel', 'route' => 'grade-levels', 'folder' => 'grade-levels', 'title' => 'Tingkat Kelas', 'perm' => 'grade-levels', 'columns' => ['name' => 'Nama', 'level_number' => 'Urutan'], 'store' => ['name' => 'required|string|max:255', 'level_number' => 'required|integer|min:1', 'description' => 'nullable|string']],
    ['name' => 'Major', 'model' => 'Major', 'route' => 'majors', 'folder' => 'majors', 'title' => 'Jurusan', 'perm' => 'majors', 'columns' => ['name' => 'Nama', 'code' => 'Kode'], 'store' => ['name' => 'required|string|max:255', 'code' => 'required|string|max:50', 'description' => 'nullable|string']],
    ['name' => 'Room', 'model' => 'Room', 'route' => 'rooms', 'folder' => 'rooms', 'title' => 'Ruangan', 'perm' => 'rooms', 'columns' => ['name' => 'Nama', 'capacity' => 'Kapasitas'], 'store' => ['name' => 'required|string|max:255', 'capacity' => 'nullable|integer|min:1', 'description' => 'nullable|string']],
    ['name' => 'LessonHour', 'model' => 'LessonHour', 'route' => 'lesson-hours', 'folder' => 'lesson-hours', 'title' => 'Jam Pelajaran', 'perm' => 'lesson-hours', 'columns' => ['name' => 'Nama', 'start_time' => 'Mulai', 'end_time' => 'Selesai'], 'store' => ['name' => 'required|string|max:255', 'start_time' => 'required|date_format:H:i', 'end_time' => 'required|date_format:H:i|after:start_time']],
    ['name' => 'SubjectGroup', 'model' => 'SubjectGroup', 'route' => 'subject-groups', 'folder' => 'subject-groups', 'title' => 'Kelompok Mapel', 'perm' => 'subjects', 'columns' => ['name' => 'Nama', 'code' => 'Kode'], 'store' => ['name' => 'required|string|max:255', 'code' => 'required|string|max:50']],
    ['name' => 'Subject', 'model' => 'Subject', 'route' => 'subjects', 'folder' => 'subjects', 'title' => 'Mata Pelajaran', 'perm' => 'subjects', 'columns' => ['code' => 'Kode', 'name' => 'Nama'], 'store' => ['subject_group_id' => 'nullable|exists:subject_groups,id', 'code' => 'required|string|max:50', 'name' => 'required|string|max:255', 'description' => 'nullable|string']],
    ['name' => 'AssessmentComponent', 'model' => 'AssessmentComponent', 'route' => 'assessment-components', 'folder' => 'assessment-components', 'title' => 'Komponen Penilaian', 'perm' => 'grades', 'columns' => ['code' => 'Kode', 'name' => 'Nama', 'weight' => 'Bobot'], 'store' => ['name' => 'required|string|max:255', 'code' => 'required|string|max:50', 'weight' => 'required|numeric|min:0|max:100', 'sort_order' => 'nullable|integer|min:0']],
    ['name' => 'FeeType', 'model' => 'FeeType', 'route' => 'fee-types', 'folder' => 'fee-types', 'title' => 'Jenis Biaya', 'perm' => 'payments', 'columns' => ['name' => 'Nama', 'amount' => 'Nominal'], 'store' => ['name' => 'required|string|max:255', 'amount' => 'required|numeric|min:0', 'description' => 'nullable|string']],
    ['name' => 'Announcement', 'model' => 'Announcement', 'route' => 'announcements', 'folder' => 'announcements', 'title' => 'Pengumuman', 'perm' => 'announcements', 'columns' => ['title' => 'Judul', 'type' => 'Tipe'], 'store' => ['title' => 'required|string|max:255', 'content' => 'required|string', 'type' => 'nullable|string|max:50', 'published_at' => 'nullable|date', 'expires_at' => 'nullable|date|after:published_at']],
];

$bindings = [];

foreach ($modules as $m) {
    $name = $m['name'];
    writeFile("{$base}/app/Repositories/Contracts/{$name}RepositoryInterface.php", moduleRepoContract($name));
    writeFile("{$base}/app/Repositories/Eloquent/{$name}Repository.php", moduleRepo($name, $m['model']));
    writeFile("{$base}/app/Services/{$name}Service.php", moduleService($name, $m['model']));

    $updateRules = $m['store'];
    writeFile("{$base}/app/Http/Requests/{$name}/Store{$name}Request.php", storeRequest($name, $m['store']));
    writeFile("{$base}/app/Http/Requests/{$name}/Update{$name}Request.php", updateRequest($name, $updateRules));
    writeFile("{$base}/app/Http/Controllers/Web/{$name}Controller.php", crudController($name, $m['model'], $m['route'], $m['folder'], $m['title'], $m['perm']));

    writeFile("{$base}/resources/views/{$m['folder']}/index.blade.php", bladeIndex($m['folder'], $m['title'], $m['route'], $m['columns']));
    writeFile("{$base}/resources/views/{$m['folder']}/create.blade.php", bladeCreate($m['folder'], $m['title'], $m['route']));
    writeFile("{$base}/resources/views/{$m['folder']}/edit.blade.php", bladeEdit($m['folder'], $m['title'], $m['route']));

    $formFields = '';
    foreach ($m['store'] as $field => $rule) {
        $label = ucfirst(str_replace('_', ' ', $field));
        $type = str_contains($rule, 'numeric') ? 'number' : (str_contains($field, 'content') || str_contains($field, 'description') ? 'textarea' : 'text');
        if ($type === 'textarea') {
            $formFields .= "<div class=\"mb-4\"><x-input-label for=\"{$field}\" value=\"{$label}\" /><textarea id=\"{$field}\" name=\"{$field}\" class=\"mt-1 block w-full border-gray-300 rounded-md shadow-sm\">{{ old('{$field}', \$item->{$field} ?? '') }}</textarea><x-input-error :messages=\"\$errors->get('{$field}')\" /></div>\n";
        } else {
            $inputType = str_contains($rule, 'date') ? 'date' : ($type === 'number' ? 'number' : 'text');
            $step = str_contains($rule, 'numeric') ? ' step="0.01"' : '';
            $formFields .= "<div class=\"mb-4\"><x-input-label for=\"{$field}\" value=\"{$label}\" /><x-text-input id=\"{$field}\" name=\"{$field}\" type=\"{$inputType}\" class=\"mt-1 block w-full\" value=\"{{ old('{$field}', \$item->{$field} ?? '') }}\"{$step} /><x-input-error :messages=\"\$errors->get('{$field}')\" /></div>\n";
        }
    }
    writeFile("{$base}/resources/views/{$m['folder']}/_form.blade.php", $formFields);

    $bindings[] = "        \\App\\Repositories\\Contracts\\{$name}RepositoryInterface::class => \\App\\Repositories\\Eloquent\\{$name}Repository::class,";
}

// Academic year + semester with activate
foreach ([
    ['name' => 'AcademicYear', 'model' => 'AcademicYear', 'route' => 'academic-years', 'folder' => 'academic-years', 'title' => 'Tahun Ajaran', 'perm' => 'academic-years', 'columns' => ['name' => 'Nama', 'start_date' => 'Mulai', 'end_date' => 'Selesai', 'is_active' => 'Aktif'], 'store' => ['name' => 'required|string|max:255', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date'], 'activate' => true],
    ['name' => 'Semester', 'model' => 'Semester', 'route' => 'semesters', 'folder' => 'semesters', 'title' => 'Semester', 'perm' => 'academic-years', 'columns' => ['name' => 'Nama', 'semester_number' => 'No', 'is_active' => 'Aktif'], 'store' => ['academic_year_id' => 'required|exists:academic_years,id', 'name' => 'required|string|max:255', 'semester_number' => 'required|integer|in:1,2', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date'], 'activate' => true],
] as $m) {
    $name = $m['name'];
    writeFile("{$base}/app/Repositories/Contracts/{$name}RepositoryInterface.php", moduleRepoContract($name));
    writeFile("{$base}/app/Repositories/Eloquent/{$name}Repository.php", moduleRepo($name, $m['model']));
    writeFile("{$base}/app/Services/{$name}Service.php", moduleService($name, $m['model']));
    writeFile("{$base}/app/Http/Requests/{$name}/Store{$name}Request.php", storeRequest($name, $m['store']));
    writeFile("{$base}/app/Http/Requests/{$name}/Update{$name}Request.php", updateRequest($name, $m['store']));
    writeFile("{$base}/app/Http/Controllers/Web/{$name}Controller.php", crudController($name, $m['model'], $m['route'], $m['folder'], $m['title'], $m['perm'], $m['activate']));

    writeFile("{$base}/resources/views/{$m['folder']}/index.blade.php", bladeIndex($m['folder'], $m['title'], $m['route'], $m['columns']));
    writeFile("{$base}/resources/views/{$m['folder']}/create.blade.php", bladeCreate($m['folder'], $m['title'], $m['route']));
    writeFile("{$base}/resources/views/{$m['folder']}/edit.blade.php", bladeEdit($m['folder'], $m['title'], $m['route']));

    $formFields = '';
    foreach ($m['store'] as $field => $rule) {
        $label = ucfirst(str_replace('_', ' ', $field));
        $inputType = str_contains($rule, 'date') ? 'date' : (str_contains($rule, 'integer') ? 'number' : 'text');
        $formFields .= "<div class=\"mb-4\"><x-input-label for=\"{$field}\" value=\"{$label}\" /><x-text-input id=\"{$field}\" name=\"{$field}\" type=\"{$inputType}\" class=\"mt-1 block w-full\" value=\"{{ old('{$field}', \$item->{$field} ?? '') }}\" /><x-input-error :messages=\"\$errors->get('{$field}')\" /></div>\n";
    }
    writeFile("{$base}/resources/views/{$m['folder']}/_form.blade.php", $formFields);

    $bindings[] = "        \\App\\Repositories\\Contracts\\{$name}RepositoryInterface::class => \\App\\Repositories\\Eloquent\\{$name}Repository::class,";
}

writeFile("{$base}/resources/views/partials/flash.blade.php", <<<'BLADE'
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
@endif
BLADE);

echo "\nStandard modules generated.\n";
echo "Bindings count: ".count($bindings)."\n";
file_put_contents("{$base}/scripts/generated-bindings.txt", implode("\n", $bindings));
