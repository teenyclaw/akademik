<?php

$base = dirname(__DIR__);

function w(string $path, string $content): void {
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents($path, $content);
}

// Helper to create standard form requests
function makeRequests(string $module, array $storeRules, array $updateRules = null): void {
    global $base;
    $updateRules = $updateRules ?? $storeRules;
    $store = var_export($storeRules, true);
    $update = var_export($updateRules, true);
    w("{$base}/app/Http/Requests/{$module}/Store{$module}Request.php", "<?php\n\nnamespace App\\Http\\Requests\\{$module};\n\nuse Illuminate\\Foundation\\Http\\FormRequest;\n\nclass Store{$module}Request extends FormRequest\n{\n    public function authorize(): bool { return true; }\n    public function rules(): array { return {$store}; }\n}\n");
    w("{$base}/app/Http/Requests/{$module}/Update{$module}Request.php", "<?php\n\nnamespace App\\Http\\Requests\\{$module};\n\nclass Update{$module}Request extends Store{$module}Request {}\n");
}

function makeViews(string $folder, string $title, string $route, array $columns, string $form): void {
    global $base;
    $headers = $cells = '';
    foreach ($columns as $k => $l) {
        $headers .= "<th class=\"px-4 py-2 text-left\">{$l}</th>";
        $cells .= "<td class=\"px-4 py-2\">{{ \$item->{$k} }}</td>";
    }
    w("{$base}/resources/views/{$folder}/index.blade.php", "<x-app-layout><x-slot name=\"header\"><div class=\"flex justify-between\"><h2 class=\"font-semibold text-xl\">{$title}</h2><a href=\"{{ route('{$route}.create') }}\" class=\"px-4 py-2 bg-indigo-600 text-white rounded-md text-sm\">Tambah</a></div></x-slot><div class=\"py-12\"><div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">@include('partials.flash')<div class=\"bg-white shadow-sm sm:rounded-lg overflow-x-auto\"><table class=\"min-w-full\"><thead class=\"bg-gray-50\"><tr>{$headers}<th class=\"px-4 py-2\">Aksi</th></tr></thead><tbody>@forelse(\$items as \$item)<tr class=\"border-t\">{$cells}<td class=\"px-4 py-2\"><a href=\"{{ route('{$route}.edit', \$item) }}\" class=\"text-indigo-600\">Edit</a> <form action=\"{{ route('{$route}.destroy', \$item) }}\" method=\"POST\" class=\"inline\" onsubmit=\"return confirm('Hapus?')\">@csrf @method('DELETE')<button class=\"text-red-600\">Hapus</button></form></td></tr>@empty<tr><td colspan=\"99\" class=\"px-4 py-4 text-center\">Belum ada data.</td></tr>@endforelse</tbody></table><div class=\"p-4\">{{ \$items->links() }}</div></div></div></div></x-app-layout>");
    w("{$base}/resources/views/{$folder}/create.blade.php", "<x-app-layout><x-slot name=\"header\"><h2 class=\"font-semibold text-xl\">Tambah {$title}</h2></x-slot><div class=\"py-12\"><div class=\"max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg\"><form action=\"{{ route('{$route}.store') }}\" method=\"POST\" enctype=\"multipart/form-data\">@csrf @include('{$folder}._form')<div class=\"mt-4\"><x-primary-button>Simpan</x-primary-button></div></form></div></div></x-app-layout>");
    w("{$base}/resources/views/{$folder}/edit.blade.php", "<x-app-layout><x-slot name=\"header\"><h2 class=\"font-semibold text-xl\">Edit {$title}</h2></x-slot><div class=\"py-12\"><div class=\"max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg\"><form action=\"{{ route('{$route}.update', \$item) }}\" method=\"POST\" enctype=\"multipart/form-data\">@csrf @method('PUT') @include('{$folder}._form')<div class=\"mt-4\"><x-primary-button>Perbarui</x-primary-button></div></form></div></div></x-app-layout>");
    w("{$base}/resources/views/{$folder}/_form.blade.php", $form);
}

// Teacher
makeRequests('Teacher', [
    'nip' => 'nullable|string|max:50', 'name' => 'required|string|max:255', 'gender' => 'nullable|in:male,female',
    'birth_place' => 'nullable|string|max:100', 'birth_date' => 'nullable|date', 'religion' => 'nullable|string|max:50',
    'address' => 'nullable|string', 'phone' => 'nullable|string|max:20', 'specialization' => 'nullable|string|max:100',
    'status' => 'nullable|string|max:20', 'joined_at' => 'nullable|date', 'photo' => 'nullable|image|max:2048',
]);
w("{$base}/app/Http/Controllers/Web/TeacherController.php", file_get_contents("{$base}/scripts/stubs/TeacherController.php.stub") ?: '');

echo "Run stub files separately\n";
