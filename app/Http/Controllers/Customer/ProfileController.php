<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pelanggan.
     */
    public function index()
    {
        /** @var \App\Models\Pelanggan $pelanggan */
        $pelanggan = Auth::guard('pelanggan')->user();
        return view('customer.profile', compact('pelanggan'));
    }

    /**
     * Memperbarui data profil pelanggan.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Pelanggan $pelanggan */
        $pelanggan = Auth::guard('pelanggan')->user();

        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'no_tlp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
        ];

        // Jika user mengisi kolom password lama, artinya dia ingin mengganti password
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password:pelanggan'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules, [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $pelanggan->nama = $validated['nama'];
        $pelanggan->no_tlp = $validated['no_tlp'];
        $pelanggan->alamat = $validated['alamat'];

        if ($request->filled('password')) {
            $pelanggan->password = Hash::make($validated['password']);
        }

        $pelanggan->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
