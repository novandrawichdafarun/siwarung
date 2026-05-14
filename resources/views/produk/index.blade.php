<x-app-layout>
    <x-slot name="header">Manajemen Produk</x-slot>

    <div class="p-6">
        <div class="flex justify-between mb-4">
            <h2>Daftar Produk</h2>
            <a href="{{ route('produk.create') }}">+ Tambah Produk</a>
        </div>

        <livewire:produk-table />
    </div>
</x-app-layout>
