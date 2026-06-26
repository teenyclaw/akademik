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
