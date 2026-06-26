<?php

$base = dirname(__DIR__);

$views = [
    'teachers' => ['title' => 'Guru', 'route' => 'teachers', 'cols' => ['nip' => 'NIP', 'name' => 'Nama', 'phone' => 'Telepon']],
    'students' => ['title' => 'Siswa', 'route' => 'students', 'cols' => ['nis' => 'NIS', 'name' => 'Nama', 'class_id' => 'Kelas ID']],
    'guardians' => ['title' => 'Orang Tua', 'route' => 'guardians', 'cols' => ['name' => 'Nama', 'phone' => 'Telepon', 'email' => 'Email']],
    'class-schedules' => ['title' => 'Jadwal Pelajaran', 'route' => 'class-schedules', 'cols' => ['day' => 'Hari', 'class_id' => 'Kelas']],
    'assessments' => ['title' => 'Penilaian', 'route' => 'assessments', 'cols' => ['name' => 'Nama', 'max_score' => 'Nilai Max']],
    'payment-bills' => ['title' => 'Tagihan', 'route' => 'payment-bills', 'cols' => ['amount' => 'Nominal', 'status' => 'Status']],
    'payment-records' => ['title' => 'Pembayaran', 'route' => 'payment-records', 'cols' => ['amount' => 'Nominal', 'payment_date' => 'Tanggal']],
    'learning-materials' => ['title' => 'Materi Pembelajaran', 'route' => 'learning-materials', 'cols' => ['title' => 'Judul']],
    'assignments' => ['title' => 'Tugas', 'route' => 'assignments', 'cols' => ['title' => 'Judul', 'deadline' => 'Deadline']],
    'assignment-submissions' => ['title' => 'Pengumpulan Tugas', 'route' => 'assignment-submissions', 'cols' => ['status' => 'Status', 'submitted_at' => 'Dikumpulkan']],
    'final-grades' => ['title' => 'Nilai Akhir', 'route' => 'final-grades', 'cols' => ['score' => 'Nilai', 'grade_letter' => 'Huruf']],
    'report-cards' => ['title' => 'Rapor', 'route' => 'report-cards', 'cols' => ['status' => 'Status', 'generated_at' => 'Digenerate']],
];

foreach ($views as $folder => $cfg) {
    $headers = $cells = '';
    foreach ($cfg['cols'] as $k => $l) {
        $headers .= "<th class=\"px-4 py-2 text-left\">{$l}</th>";
        $val = in_array($k, ['class_id']) ? "{{ \$item->schoolClass?->name ?? \$item->{$k} }}" : "{{ \$item->{$k} }}";
        if ($k === 'status' && str_contains($folder, 'payment')) {
            $val = "{{ \$item->status?->value ?? \$item->status }}";
        }
        $cells .= "<td class=\"px-4 py-2\">{$val}</td>";
    }
    $route = $cfg['route'];
    $title = $cfg['title'];
    $extra = '';
    if ($folder === 'report-cards') {
        $extra = '<a href="{{ route(\'report-cards.show\', $item) }}" class="text-green-600">Lihat</a> <a href="{{ route(\'report-cards.pdf\', $item) }}" class="text-blue-600">PDF</a> ';
    }
    if ($folder === 'final-grades') {
        file_put_contents("{$base}/resources/views/{$folder}/index.blade.php", "<x-app-layout><x-slot name=\"header\"><h2 class=\"font-semibold text-xl\">{$title}</h2></x-slot><div class=\"py-12\"><div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">@include('partials.flash')<div class=\"bg-white p-6 shadow-sm sm:rounded-lg mb-6\"><form action=\"{{ route('final-grades.calculate') }}\" method=\"POST\">@csrf<div class=\"grid grid-cols-3 gap-4\"><select name=\"class_id\" class=\"border-gray-300 rounded-md\" required>@foreach(\$classes as \$c)<option value=\"{{ \$c->id }}\">{{ \$c->name }}</option>@endforeach</select><select name=\"semester_id\" class=\"border-gray-300 rounded-md\" required>@foreach(\$semesters as \$s)<option value=\"{{ \$s->id }}\">{{ \$s->name }}</option>@endforeach</select><select name=\"subject_id\" class=\"border-gray-300 rounded-md\" required>@foreach(\$subjects as \$s)<option value=\"{{ \$s->id }}\">{{ \$s->name }}</option>@endforeach</select></div><button class=\"mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md\">Hitung Nilai Akhir</button></form></div><div class=\"bg-white shadow-sm sm:rounded-lg overflow-x-auto\"><table class=\"min-w-full\"><thead class=\"bg-gray-50\"><tr>{$headers}</tr></thead><tbody>@forelse(\$items as \$item)<tr class=\"border-t\">{$cells}</tr>@empty<tr><td colspan=\"99\" class=\"px-4 py-4 text-center\">Belum ada data.</td></tr>@endforelse</tbody></table><div class=\"p-4\">{{ \$items->links() }}</div></div></div></div></x-app-layout>");
        continue;
    }
    $createLink = $folder !== 'final-grades' ? "<a href=\"{{ route('{$route}.create') }}\" class=\"px-4 py-2 bg-indigo-600 text-white rounded-md text-sm\">Tambah</a>" : '';
    file_put_contents("{$base}/resources/views/{$folder}/index.blade.php", "<x-app-layout><x-slot name=\"header\"><div class=\"flex justify-between\"><h2 class=\"font-semibold text-xl\">{$title}</h2>{$createLink}</div></x-slot><div class=\"py-12\"><div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">@include('partials.flash')<div class=\"bg-white shadow-sm sm:rounded-lg overflow-x-auto\"><table class=\"min-w-full\"><thead class=\"bg-gray-50\"><tr>{$headers}<th class=\"px-4 py-2\">Aksi</th></tr></thead><tbody>@forelse(\$items as \$item)<tr class=\"border-t\">{$cells}<td class=\"px-4 py-2\">{$extra}<a href=\"{{ route('{$route}.edit', \$item) }}\" class=\"text-indigo-600\">Edit</a> @if('{$route}' !== 'final-grades')<form action=\"{{ route('{$route}.destroy', \$item) }}\" method=\"POST\" class=\"inline\" onsubmit=\"return confirm('Hapus?')\">@csrf @method('DELETE')<button class=\"text-red-600\">Hapus</button></form>@endif</td></tr>@empty<tr><td colspan=\"99\" class=\"px-4 py-4 text-center\">Belum ada data.</td></tr>@endforelse</tbody></table><div class=\"p-4\">{{ \$items->links() }}</div></div></div></div></x-app-layout>");
}

// Special views
file_put_contents("{$base}/resources/views/attendances/index.blade.php", <<<'BLADE'
<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Absensi</h2></x-slot>
<div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
@include('partials.flash')
<form method="GET" class="bg-white p-4 mb-4 shadow-sm sm:rounded-lg flex gap-4">
<select name="class_id" class="border-gray-300 rounded-md" required>@foreach($classes as $c)<option value="{{ $c->id }}" @selected($classId == $c->id)>{{ $c->name }}</option>@endforeach</select>
<input type="date" name="date" value="{{ $date }}" class="border-gray-300 rounded-md" required>
<button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Tampilkan</button>
</form>
@if($classId)
<form action="{{ route('attendances.store') }}" method="POST" class="bg-white p-6 shadow-sm sm:rounded-lg">
@csrf<input type="hidden" name="class_id" value="{{ $classId }}"><input type="hidden" name="date" value="{{ $date }}">
<table class="min-w-full"><thead><tr><th class="px-4 py-2 text-left">NIS</th><th class="px-4 py-2 text-left">Nama</th><th class="px-4 py-2 text-left">Status</th></tr></thead>
<tbody>@foreach($records as $row)<tr class="border-t"><td class="px-4 py-2">{{ $row['student']->nis }}</td><td class="px-4 py-2">{{ $row['student']->name }}</td>
<td class="px-4 py-2"><select name="attendances[{{ $row['student']->id }}]" class="border-gray-300 rounded-md">
@foreach(['H','S','I','A'] as $s)<option value="{{ $s }}" @selected(($row['attendance']->status->value ?? $row['attendance']->status ?? 'H') == $s)>{{ $s }}</option>@endforeach
</select></td></tr>@endforeach</tbody></table>
<button class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md">Simpan Absensi</button>
</form>@endif
</div></div></x-app-layout>
BLADE);

file_put_contents("{$base}/resources/views/grades/index.blade.php", <<<'BLADE'
<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl">Input Nilai</h2></x-slot>
<div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">@include('partials.flash')
<div class="bg-white p-4 mb-4 shadow-sm sm:rounded-lg"><form method="GET"><select name="assessment_id" class="border-gray-300 rounded-md" onchange="this.form.submit()">
<option value="">Pilih Penilaian</option>@foreach($assessments as $a)<option value="{{ $a->id }}" @selected($assessmentId == $a->id)>{{ $a->name }} - {{ $a->subject?->name }}</option>@endforeach
</select></form></div>
@if($assessmentId)<form action="{{ route('grades.store') }}" method="POST" class="bg-white p-6 shadow-sm sm:rounded-lg">@csrf<input type="hidden" name="assessment_id" value="{{ $assessmentId }}">
<table class="min-w-full"><thead><tr><th class="px-4 py-2 text-left">NIS</th><th class="px-4 py-2 text-left">Nama</th><th class="px-4 py-2 text-left">Nilai</th></tr></thead>
<tbody>@foreach($records as $row)<tr class="border-t"><td class="px-4 py-2">{{ $row['student']->nis }}</td><td class="px-4 py-2">{{ $row['student']->name }}</td>
<td class="px-4 py-2"><input type="number" step="0.01" name="scores[{{ $row['student']->id }}]" value="{{ $row['grade']->score ?? '' }}" class="border-gray-300 rounded-md w-24"></td></tr>@endforeach</tbody></table>
<button class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md">Simpan Nilai</button></form>@endif
</div></div></x-app-layout>
BLADE);

file_put_contents("{$base}/resources/views/reports/index.blade.php", <<<'BLADE'
<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Laporan & Export</h2></x-slot>
<div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
@include('partials.flash')
<div class="bg-white p-6 shadow-sm sm:rounded-lg space-y-3">
<a href="{{ route('reports.export.students') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded-md text-center">Export Siswa (Excel)</a>
<a href="{{ route('reports.export.teachers') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded-md text-center">Export Guru (Excel)</a>
<a href="{{ route('reports.export.payments') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded-md text-center">Export Pembayaran (Excel)</a>
<form action="{{ route('reports.export.attendance') }}" method="GET" class="space-y-2"><select name="class_id" class="w-full border-gray-300 rounded-md">@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select><input type="date" name="date" class="w-full border-gray-300 rounded-md"><button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md">Export Absensi</button></form>
<form action="{{ route('reports.export.grades') }}" method="GET" class="space-y-2"><select name="semester_id" class="w-full border-gray-300 rounded-md">@foreach($semesters as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select><select name="class_id" class="w-full border-gray-300 rounded-md">@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select><button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md">Export Nilai</button></form>
</div></div></div></x-app-layout>
BLADE);

echo "Views generated\n";
