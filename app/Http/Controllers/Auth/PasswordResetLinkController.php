<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan form lupa password.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle proses request link reset password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        // Cek apakah email terdaftar di Pelanggan
        $isPelanggan = Pelanggan::where('email', $email)->exists();
        // Cek apakah email terdaftar di Admin
        $isAdmin = Admin::where('email', $email)->exists();

        if (!$isPelanggan && !$isAdmin) {
            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
        }

        // Tentukan broker mana yang digunakan
        $broker = $isPelanggan ? 'pelanggans' : 'admins';

        // Mengirim link reset password
        $status = Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
