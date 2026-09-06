<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class ProfilController (Admin)
 *
 * Bertanggung jawab mengelola pengaturan profil pribadi administrator,
 * termasuk perubahan nama, email, dan kata sandi.
 */
class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil admin yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var \App\Models\Admin $admin */
        // Ambil data admin yang sedang aktif login menggunakan guard 'admin'
        $admin = Auth::guard('admin')->user();

        return view('admin.profil', compact('admin'));
    }

    /**
     * Memperbarui informasi profil admin (nama, email, dan opsional kata sandi).
     *
     * Validasi:
     * - Nama dan email wajib diisi.
     * - Email harus unik kecuali milik admin itu sendiri.
     * - Jika ingin ganti password: wajib isi password lama, password baru, dan konfirmasi.
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        // Validasi semua input yang diterima dari form profil
        $request->validate([
            'nama'             => ['required', 'string', 'max:100'],

            // Email wajib unik, tapi dikecualikan untuk email milik admin ini sendiri
            'email'            => ['required', 'string', 'email', 'max:100', 'unique:admin,email,' . $admin->id_admin . ',id_admin'],

            // Password lama wajib diisi jika kolom password baru diisi
            'current_password' => ['nullable', 'required_with:password', 'current_password:admin'],

            // Password baru minimal 8 karakter dan harus dikonfirmasi
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            // Pesan validasi dalam Bahasa Indonesia
            'nama.required'                        => 'Nama lengkap wajib diisi.',
            'email.required'                       => 'Alamat email wajib diisi.',
            'email.email'                          => 'Format email tidak valid.',
            'email.unique'                         => 'Email ini sudah digunakan.',
            'current_password.required_with'       => 'Password lama wajib diisi jika ingin mengubah password baru.',
            'current_password.current_password'    => 'Password lama yang Anda masukkan salah.',
            'password.min'                         => 'Password baru minimal harus 8 karakter.',
            'password.confirmed'                   => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Perbarui nama dan email admin
        $admin->nama  = $request->nama;
        $admin->email = $request->email;

        // Jika admin mengisi password baru, hash dan simpan
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // Simpan perubahan ke database
        $admin->save();

        // Arahkan kembali ke halaman profil dengan pesan sukses
        return redirect()->route('admin.profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
