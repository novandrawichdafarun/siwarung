<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Http\Requests\TambahStokRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
        // Inject StockService via constructor — Laravel auto-resolve
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $kategori = Category::orderBy('nama_kategori')->get();

        return view('produk.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategori = Category::orderBy('nama_kategori')->get();
        return view('produk.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['warung_id'] = auth()->user()->warung_id;
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        $produk = Product::create($data);

        if ($produk->stok > 0) {
            $this->stockService->tambahStok(
                product: $produk,
                jumlah: 0,
                keterangan: 'Stok awal produk'
            );
        }

        return redirect()->route('produk.index')
            ->with('success', "Produk {$produk->nama_produk} Berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $produk): View
    {
        abort_if($produk->warung_id !== auth()->user()->warung_id, 403);

        $kategori = Category::orderBy('nama_kategori')->get();
        $riwayatStok = StockMovement::where('product_id', $produk->id)
            ->latest()
            ->take(10)
            ->get();

        return view('produk.edit', compact('produk', 'kategori', 'riwayatStok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukRequest $request, Product $produk): RedirectResponse
    {
        abort_if($produk->warung_id !== auth()->user()->warung_id, 403);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', "Produk {$produk->nama_produk} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produk): RedirectResponse
    {
        abort_if($produk->warung_id !== auth()->user()->warung_id, 403);

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete(); // SoftDelete

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function tambahStok(TambahStokRequest $request, Product $produk): RedirectResponse
    {
        abort_if($produk->warung_id !== auth()->user()->warung_id, 403);

        $this->stockService->tambahStok(
            product: $produk,
            jumlah: $request->jumlah,
            keterangan: $request->keterangan
        );

        return redirect()->route('produk.edit', $produk)
            ->with('success', "Stok berhasil ditambah {$request->jumlah} unit.");
    }
}
