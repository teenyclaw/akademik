<x-app-layout>
    <x-slot name="header"><div class="flex justify-between"><h2 class="font-semibold text-xl">Kelas</h2><a href="{{ route('classes.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Tambah</a></div></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">@include('partials.flash')
        <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto"><table class="min-w-full"><thead class="bg-gray-50"><tr>
            <th class="px-4 py-2 text-left">Nama</th><th class="px-4 py-2 text-left">Tingkat</th><th class="px-4 py-2 text-left">TA</th><th class="px-4 py-2">Aksi</th>
        </tr></thead><tbody>@forelse($items as $item)<tr class="border-t">
            <td class="px-4 py-2">{{ $item->name }}</td><td class="px-4 py-2">{{ $item->gradeLevel?->name }}</td><td class="px-4 py-2">{{ $item->academicYear?->name }}</td>
            <td class="px-4 py-2"><a href="{{ route('classes.edit', $item) }}" class="text-indigo-600">Edit</a>
            <form action="{{ route('classes.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form></td>
        </tr>@empty<tr><td colspan="4" class="px-4 py-4 text-center">Belum ada data.</td></tr>@endforelse</tbody></table><div class="p-4">{{ $items->links() }}</div></div>
    </div></div>
</x-app-layout>