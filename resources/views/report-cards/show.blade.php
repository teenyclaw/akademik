<x-app-layout><x-slot name="header"><div class="flex justify-between"><h2 class="font-semibold text-xl">Rapor - {{ $reportCard->student->name }}</h2>
<a href="{{ route('report-cards.pdf', $reportCard) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Download PDF</a></div></x-slot>
<div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
<p><strong>NIS:</strong> {{ $reportCard->student->nis }} | <strong>Kelas:</strong> {{ $reportCard->schoolClass?->name }}</p>
<table class="min-w-full mt-4"><thead><tr><th class="px-4 py-2 text-left">Mapel</th><th class="px-4 py-2 text-left">Nilai</th><th class="px-4 py-2 text-left">Huruf</th></tr></thead>
<tbody>@foreach($reportCard->details as $d)<tr class="border-t"><td class="px-4 py-2">{{ $d->subject?->name }}</td><td class="px-4 py-2">{{ $d->score }}</td><td class="px-4 py-2">{{ $d->grade_letter }}</td></tr>@endforeach</tbody></table>
</div></div></x-app-layout>
