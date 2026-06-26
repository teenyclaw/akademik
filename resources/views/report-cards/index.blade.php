<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Rapor</h2></x-slot>
<div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">@include('partials.flash')
<div class="bg-white p-6 shadow-sm sm:rounded-lg mb-6"><form action="{{ route('report-cards.generate') }}" method="POST">@csrf
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<select name="class_id" class="border-gray-300 rounded-md" required>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
<select name="semester_id" class="border-gray-300 rounded-md" required>@foreach($semesters as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
<select name="student_id" class="border-gray-300 rounded-md"><option value="">Semua Siswa di Kelas</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
</div><button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md">Generate Rapor</button></form></div>
<div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto"><table class="min-w-full"><thead class="bg-gray-50"><tr>
<th class="px-4 py-2 text-left">Siswa</th><th class="px-4 py-2 text-left">Status</th><th class="px-4 py-2 text-left">Digenerate</th><th class="px-4 py-2">Aksi</th>
</tr></thead><tbody>@forelse($items as $item)<tr class="border-t">
<td class="px-4 py-2">{{ $item->student?->name }}</td><td class="px-4 py-2">{{ $item->status }}</td><td class="px-4 py-2">{{ $item->generated_at }}</td>
<td class="px-4 py-2"><a href="{{ route('report-cards.show', $item) }}" class="text-green-600">Lihat</a>
<a href="{{ route('report-cards.pdf', $item) }}" class="text-blue-600">PDF</a>
<form action="{{ route('report-cards.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td>
</tr>@empty<tr><td colspan="4" class="px-4 py-4 text-center">Belum ada data.</td></tr>@endforelse</tbody></table>
<div class="p-4">{{ $items->links() }}</div></div></div></div></x-app-layout>
