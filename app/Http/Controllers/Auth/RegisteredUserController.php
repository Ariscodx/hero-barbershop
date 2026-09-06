<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Services\WablasService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * Register hanya diperuntukkan bagi Pelanggan.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Menyimpan data ke tabel pelanggan dan login menggunakan guard pelanggan.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:pelanggan,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_tlp'   => ['nullable', 'string', 'max:13'],
            'alamat'   => ['nullable', 'string'],
        ]);

        $pelanggan = Pelanggan::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_tlp'   => $request->no_tlp,
            'alamat'   => $request->alamat,
        ]);

        event(new Registered($pelanggan));

        Auth::guard('pelanggan')->login($pelanggan);

        // Kirim Notifikasi WhatsApp via Wablas jika nomor telepon diisi
        if ($pelanggan->no_tlp) {
            try {
                $pesan = "Halo *{$pelanggan->nama}*, 👋\n\nSelamat! Akun kamu berhasil didaftarkan di *Hero Barbershop*. ✂️\n\nSekarang kamu bisa mulai booking jadwal cukur dengan mudah secara online.\nJangan lupa rutin cukur biar makin keren! ✨";

                $wablas = new WablasService();
                $wablas->send($pelanggan->no_tlp, $pesan);
            } catch (\Exception $e) {
                Log::error('Wablas WA Error Register: ' . $e->getMessage());
            }
        }

        return redirect()->route('customer.dashboard');
    }
}
