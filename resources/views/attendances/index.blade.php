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
@foreach(['H','S','I','A'] as $s)<option value="{{ $s }}" @selected(($row['attendance']?->status?->value ?? $row['attendance']?->status ?? 'H') == $s)>{{ $s }}</option>@endforeach
</select></td></tr>@endforeach</tbody></table>
<button class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md">Simpan Absensi</button>
</form>@endif
</div></div></x-app-layout>
