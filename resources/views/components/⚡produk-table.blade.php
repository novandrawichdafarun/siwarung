<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

new class extends Component {
    public string $search = '';
    public string $filterKategori = '';
    public string $filterStatus = '';

    public function render(): View
    {
        $produk = Product::query()
            ->with('category') // eager load hindari N+1
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('category_id', $this->filterKategori);
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus === 'aktif');
            })
            ->orderBy('nama_produk')
            ->paginate(10);

        $kategori = Category::orderBy('nama_kategori')->get();

        return view('livewire.produk-table', compact('produk', 'kategori'));
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterKategori = '';
        $this->filterStatus = '';
    }
};
?>

<div>
    {{-- Search & Filter Bar --}}
    <div class="flex gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama produk..." class="...">

        <select wire:model.live="filterKategori" class="...">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterStatus" class="...">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>

        <button wire:click="resetFilter" class="...">Reset</button>
    </div>

    {{-- Tabel Produk --}}
    <table class="w-full">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $item)
                <tr>
                    <td>
                        @if ($item->foto)
                            <img src="{{ Storage::url($item->foto) }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-xs text-gray-400">No foto</span>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->category?->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->formatted_harga_jual }}</td>
                    <td>
                        {{-- Alert merah jika stok rendah --}}
                        <span class="{{ $item->isLowStock() ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->stok }}
                        </span>
                        @if ($item->isLowStock())
                            <span class="text-xs text-red-500">(rendah!)</span>
                        @endif
                    </td>
                    <td>
                        <span
                            class="{{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}
                                  px-2 py-1 rounded text-xs">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="flex gap-2">
                        <a href="{{ route('produk.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('produk.destroy', $item) }}">
                            @method('DELETE')
                            @csrf
                            <button onclick="return confirm('Hapus produk ini?')">Hapus</button>
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
