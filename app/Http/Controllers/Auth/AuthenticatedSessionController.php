<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * Logika auth ditangani LANGSUNG di sini (bukan di LoginRequest)
     * agar redirect dapat dilakukan SEBELUM session regenerate,
     * sehingga guard state tidak hilang akibat regenerasi session.
     *
     * Urutan pengecekan:
     * 1. Admin berdasarkan email
     * 2. Pelanggan berdasarkan email
     * 3. Pelanggan berdasarkan nomor HP (no_tlp)
     */
    public function store(Request $request): RedirectResponse
    {
        // ── Validasi Input ───────────────────────────────────────────────
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // ── Rate Limiting ────────────────────────────────────────────────
        $throttleKey = Str::transliterate(
            Str::lower($request->string('email')) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $credential = $request->string('email')->toString();
        $password   = $request->string('password')->toString();

        // ── 1. Cek tabel Admin berdasarkan email ─────────────────────────
        $admin = Admin::where('email', $credential)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            // Login berhasil sebagai Admin
            Auth::guard('admin')->login($admin);
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Langsung redirect ke dashboard admin — tanpa melalui halaman lain
            return redirect()->route('admin.dashboard');
        }

        // ── 2. Cek tabel Pelanggan berdasarkan email ──────────────────────
        $pelanggan = Pelanggan::where('email', $credential)->first();
        if ($pelanggan && Hash::check($password, $pelanggan->password)) {
            // Login berhasil sebagai Pelanggan
            Auth::guard('pelanggan')->login($pelanggan);
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Langsung redirect ke dashboard pelanggan — tanpa melalui halaman lain
            return redirect()->route('customer.dashboard');
        }

        // ── 3. Cek tabel Pelanggan berdasarkan nomor HP ───────────────────
        $pelanggan = Pelanggan::where('no_tlp', $credential)->first();
        if ($pelanggan && Hash::check($password, $pelanggan->password)) {
            // Login berhasil sebagai Pelanggan via nomor HP
            Auth::guard('pelanggan')->login($pelanggan);
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            // Langsung redirect ke dashboard pelanggan
            return redirect()->route('customer.dashboard');
        }

        // ── Akun tidak ditemukan pada kedua tabel ─────────────────────────
        RateLimiter::hit($throttleKey);

        throw ValidationException::withMessages([
            'email' => 'Email atau Password salah.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     * Logout dari guard yang aktif, kemudian redirect ke halaman welcome.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Selalu kembali ke halaman Welcome setelah logout
        return redirect()->route('welcome');
    }
}
