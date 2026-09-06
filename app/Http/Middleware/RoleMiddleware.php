<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Menggunakan guard-based check, bukan kolom role pada tabel.
     *
     * Penggunaan di route: ->middleware('role:admin') atau ->middleware('role:pelanggan')
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($role === 'admin' && Auth::guard('admin')->check()) {
            return $next($request);
        }

        if ($role === 'pelanggan' && Auth::guard('pelanggan')->check()) {
            return $next($request);
        }

        // Jika guard tidak sesuai, redirect berdasarkan siapa yang sedang login
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('pelanggan')->check()) {
            return redirect()->route('customer.dashboard');
        }

        // Belum login sama sekali
        return redirect()->route('login');
    }
}
