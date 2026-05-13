<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWarungSetup
{
    /**
     * Memastikan user sudah menyelesaikan setup warung.
     * Jika belum, redirect ke halaman setup warung.
     *
     * Dipakai sebagai lapisan kedua setelah auth middleware:
     *   Route::middleware(['auth', 'warung.setup'])->group(...)
     *
     * Super admin dilewati karena mereka tidak perlu warung.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin tidak perlu setup warung
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Kasir seharusnya selalu punya warung_id (dibuat oleh owner)
        // Jika tidak, berarti ada masalah data
        if (!$user->hasWarung()) {
            // Owner yang belum setup → arahkan ke form setup warung
            if ($user->isOwner()) {
                return redirect()->route('warung.setup')
                    ->with('info', 'Silakan lengkapi data warung Anda terlebih dahulu.');
            }

            // Kasir tanpa warung → kemungkinan akun bermasalah
            abort(403, 'Akun Anda belum terhubung ke warung manapun. Hubungi pemilik warung.');
        }

        return $next($request);
    }
}
