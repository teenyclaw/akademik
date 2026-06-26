<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl">Pengaturan Sekolah</h2></x-slot>
<div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
@include('partials.flash')
<form action="{{ route('school-settings.update') }}" method="POST">@csrf @method('PUT')
@foreach(['school_name' => 'Nama Sekolah', 'school_address' => 'Alamat', 'school_phone' => 'Telepon', 'school_email' => 'Email', 'principal_name' => 'Nama Kepala Sekolah', 'report_footer' => 'Footer Rapor'] as $key => $label)
<div class="mb-4"><x-input-label :value="$label" /><x-text-input name="{{ $key }}" class="mt-1 block w-full" value="{{ old($key, $settings[$key]['value'] ?? $settings[$key] ?? '') }}" /></div>
@endforeach
<div class="mt-4"><x-primary-button>Simpan</x-primary-button></div></form></div></div></x-app-layout>
