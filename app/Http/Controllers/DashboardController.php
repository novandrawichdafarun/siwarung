<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $warung = auth()->user()->warung;

        $data = [
            'warung' => $warung,
            'total_produk' => Product::count(),
            'produk_low_stock' => Product::lowStock()->count(),
            'total_transaksi_hari_ini' => 0,
            'omset_hari_ini' => 0,
        ];

        return view('dashboard', $data);
    }
}
