<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class ProdukTable extends Component
{
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
}
;
