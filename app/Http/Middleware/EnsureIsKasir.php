<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsKasir
{
    /**
     * Memastikan user sudah login dan memiliki role operasional
     * (owner atau kasir). Super admin tidak termasuk di sini
     * karena mereka punya panel tersendiri.
     *
     * Nama middleware "EnsureIsKasir" maksudnya: "pastikan user
     * adalah bagian dari tim kasir warung" (owner + kasir).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (! $user->canAccessPOS()) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
