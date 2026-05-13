<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(): View
    {
        $warung = auth()->user()->warung;
        return view('pengaturan.index', compact('warung'));
    }

    public function update(Request $request): RedirectResponse
    {
        $warung = auth()->user()->warung;

        $request->validate([
            'nama_warung' => [
                'required',
                'string',
                'max:255',
                'unique:warung,nama_warung,' . $warung->id
            ],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data = $request->only(['nama_warung', 'alamat', 'telepon']);

        if ($request->hasFile('logo')) {
            if ($warung->logo) {
                Storage::disk('public')->delete($warung->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $warung->update($data);

        return redirect()->route('paengaturan.index')
            ->with('success', 'Pengaturan warung berhasil disimpan.');
    }
}
