<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Nilai Akhir</h2></x-slot>
<div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">@include('partials.flash')
<div class="bg-white p-6 shadow-sm sm:rounded-lg mb-6"><form action="{{ route('final-grades.calculate') }}" method="POST">@csrf
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<select name="class_id" class="border-gray-300 rounded-md" required>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
<select name="semester_id" class="border-gray-300 rounded-md" required>@foreach($semesters as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
<select name="subject_id" class="border-gray-300 rounded-md" required>@foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
</div><button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md">Hitung Nilai Akhir</button></form></div>
<div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto"><table class="min-w-full"><thead class="bg-gray-50"><tr>
<th class="px-4 py-2 text-left">Nilai</th><th class="px-4 py-2 text-left">Huruf</th></tr></thead>
<tbody>@forelse($items as $item)<tr class="border-t"><td class="px-4 py-2">{{ $item->score }}</td><td class="px-4 py-2">{{ $item->grade_letter }}</td></tr>
@empty<tr><td colspan="2" class="px-4 py-4 text-center">Belum ada data.</td></tr>@endforelse</tbody></table>
<div class="p-4">{{ $items->links() }}</div></div></div></div></x-app-layout>
