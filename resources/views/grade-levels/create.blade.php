<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Tambah Tingkat Kelas</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm sm:rounded-lg">
            @include('partials.flash')
            <form action="{{ route('grade-levels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('grade-levels._form')
                <div class="mt-4"><x-primary-button>Simpan</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>