<x-app-layout>
    <x-slot name="breadcrumb">
        <x-breadcrumb :items="[['label' => 'Siswa']]" />
    </x-slot>

    <x-page-header title="Data Siswa" description="Kelola data siswa sekolah.">
        <x-slot:actions>
            <a href="{{ route('students.create') }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Siswa
            </a>
        </x-slot:actions>
    </x-page-header>

    @include('partials.flash')

    <x-data-table title="Daftar Siswa">
        <table class="premium-table">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td class="font-mono text-xs font-medium text-slate-500">{{ $item->nis }}</td>
                        <td class="font-semibold">{{ $item->name }}</td>
                        <td>
                            @if ($item->schoolClass)
                                <span class="inline-flex rounded-lg bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-700 dark:bg-brand-900/30 dark:text-brand-300">
                                    {{ $item->schoolClass->name }}
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="inline-flex items-center gap-3">
                                <a href="{{ route('students.edit', $item) }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700 dark:text-brand-400">Edit</a>
                                <form action="{{ route('students.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-500 transition hover:text-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-500">Belum ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot:pagination>
            {{ $items->links() }}
        </x-slot:pagination>
    </x-data-table>
</x-app-layout>
