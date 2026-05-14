<div>
    {{-- Search & Filter Bar --}}
    <div class="flex gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk..."
            class="border rounded px-2 py-1 w-full max-w-xs">

        <select wire:model.live="filterKategori" class="border rounded px-2 py-1">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterStatus" class="border rounded px-2 py-1">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>

        {{-- Catatan: Sebelumnya ada typo di kodemu (resetFilter), sudah diubah jadi resetFilters sesuai fungsi di class --}}
        <button wire:click="resetFilters" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">Reset</button>
    </div>

    {{-- Tabel Produk --}}
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b">
                <th class="py-2">Foto</th>
                <th class="py-2">Nama Produk</th>
                <th class="py-2">Kategori</th>
                <th class="py-2">Harga Jual</th>
                <th class="py-2">Stok</th>
                <th class="py-2">Status</th>
                <th class="py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2">
                        @if ($item->foto)
                            <img src="{{ Storage::url($item->foto) }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-xs text-gray-400">No foto</span>
                            </div>
                        @endif
                    </td>
                    <td class="py-2">{{ $item->nama_produk }}</td>
                    <td class="py-2">{{ $item->category?->nama_kategori ?? '-' }}</td>
                    <td class="py-2">{{ $item->formatted_harga_jual ?? $item->harga_jual }}</td>
                    <td class="py-2">
                        {{-- Alert merah jika stok rendah --}}
                        <span
                            class="{{ method_exists($item, 'isLowStock') && $item->isLowStock() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->stok }}
                        </span>
                        @if (method_exists($item, 'isLowStock') && $item->isLowStock())
                            <span class="text-xs text-red-500 block">(rendah!)</span>
                        @endif
                    </td>
                    <td class="py-2">
                        <span
                            class="{{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} px-2 py-1 rounded text-xs">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-2 flex gap-2 items-center h-full pt-4">
                        <a href="{{ route('produk.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('produk.destroy', $item) }}" class="inline">
                            @method('DELETE')
                            @csrf
                            <button onclick="return confirm('Hapus produk ini?')"
                                class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-400 py-8">
                        Tidak ada produk ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $produk->links() }}
    </div>
</div>
