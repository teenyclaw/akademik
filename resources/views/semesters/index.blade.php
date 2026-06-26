<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Semester</h2>
            @can('semesters.create')
            <a href="{{ route('semesters.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Aktif</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->name }}</td>
                            <td class="px-4 py-2">{{ $item->semester_number }}</td>
                            <td class="px-4 py-2">{{ $item->is_active }}</td>
                            <td class="px-4 py-2 space-x-2">
                                @can('academic-years.edit')
                                <a href="{{ route('semesters.edit', $item) }}" class="text-indigo-600">Edit</a>
                                @if(!$item->is_active)
                                <form action="{{ route('semesters.activate', $item) }}" method="POST" class="inline">@csrf<button class="text-green-600">Aktifkan</button></form>
                                @endif
                                @endcan
                                @can('academic-years.delete')
                                <form action="{{ route('semesters.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="99" class="px-4 py-4 text-center text-gray-500">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ $items->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>