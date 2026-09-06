<?php

namespace App\Http\Requests\Auth;

use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     * Urutan pengecekan:
     * 1. Admin berdasarkan email
     * 2. Pelanggan berdasarkan email
     * 3. Pelanggan berdasarkan nomor HP (no_tlp)
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credential = $this->string('email')->toString();
        $password   = $this->string('password')->toString();

        // ── 1. Coba login sebagai Admin (hanya via email) ──────────────────
        $admin = Admin::where('email', $credential)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // ── 2. Coba login sebagai Pelanggan via email ──────────────────────
        $pelanggan = Pelanggan::where('email', $credential)->first();
        if ($pelanggan && Hash::check($password, $pelanggan->password)) {
            Auth::guard('pelanggan')->login($pelanggan);
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // ── 3. Coba login sebagai Pelanggan via nomor HP ───────────────────
        $pelanggan = Pelanggan::where('no_tlp', $credential)->first();
        if ($pelanggan && Hash::check($password, $pelanggan->password)) {
            Auth::guard('pelanggan')->login($pelanggan);
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // ── Tidak ditemukan pada kedua tabel ──────────────────────────────
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Email atau Password salah.',
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
