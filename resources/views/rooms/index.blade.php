<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ruangan</h2>
            @can('rooms.create')
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a>
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
                        <th class="px-4 py-2 text-left">Kapasitas</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->name }}</td>
                            <td class="px-4 py-2">{{ $item->capacity }}</td>
                            <td class="px-4 py-2 space-x-2">
                                @can('rooms.edit')
                                <a href="{{ route('rooms.edit', $item) }}" class="text-indigo-600">Edit</a>
                                @endcan
                                @can('rooms.delete')
                                <form action="{{ route('rooms.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
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