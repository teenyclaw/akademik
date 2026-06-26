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
