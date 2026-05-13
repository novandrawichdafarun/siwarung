<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    public function index(): View
    {
        $karyawan = User::where('role', 'kasir')->get();

        return view('karyawan.index', compact('karyawan'));
    }

    public function create(): View
    {
        return view('karyawan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
            'warung_id' => auth()->user()->warung_id,
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Akun kasir berhasil dibuat.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->warung_id !== auth()->user()->warung_id, 403);
        abort_if($user->isOwner(), 403, 'Tidak bisa menghapus akun owner.');

        $user->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Akun kasir berhasil dihapus.');
    }
}
