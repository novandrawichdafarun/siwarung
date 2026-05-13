<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsOwner
{
    /**
     * Hanya user dengan role 'owner' yang bisa lewat.
     * Kasir diarahkan ke halaman POS.
     * Super admin diarahkan ke panel mereka sendiri (sprint berikutnya).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (! $user->isOwner()) {
            // Kasir diarahkan ke POS, bukan diblock total
            if ($user->isKasir()) {
                return redirect()->route('pos.index')
                    ->with('warning', 'Halaman ini hanya bisa diakses oleh pemilik warung.');
            }

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
