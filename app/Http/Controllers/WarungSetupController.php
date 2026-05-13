<?php

namespace App\Http\Controllers;

use App\Models\Warung;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarungSetupController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (auth()->user()->hasWarung()) {
            return redirect()->route('dasboard');
        }
        return view('warung.setup');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_warung' => ['required', 'string', 'max:255', 'unique:warung,nama_warung'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $warung = Warung::create([
            'nama_warung' => $request->nama_warung,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        auth()->user()->update(['warung_id' => $warung->id]);

        return redirect()->route('dasboard')
            ->with('success', 'Warung berhasil dibuat! Selamat datang di SIWARUNG.');
    }
}
