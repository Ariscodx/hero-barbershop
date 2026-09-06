<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasService
{
    protected string $token;
    protected string $domain;
    protected ?string $secret;

    public function __construct()
    {
        $this->token  = config('services.wablas.token');
        $this->domain = config('services.wablas.domain', 'smg.wablas.com');
        $this->secret = config('services.wablas.secret');
    }

    /**
     * Normalisasi nomor HP ke format internasional (628xxx)
     * Contoh: 081234 -> 6281234 | +6281234 -> 6281234
     */
    protected function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Kirim pesan WhatsApp via Wablas API
     * Format Authorization: token.secret_key
     */
    public function send(string $phone, string $message): bool
    {
        try {
            $normalized = $this->normalizePhone($phone);
            $endpoint   = "https://{$this->domain}/api/send-message";

            // Format Authorization: token.secret (sesuai dokumentasi Wablas)
            $authHeader = $this->secret
                ? "{$this->token}.{$this->secret}"
                : $this->token;

            $response = Http::withHeaders([
                'Authorization' => $authHeader,
            ])->asForm()->post($endpoint, [
                'phone'   => $normalized,
                'message' => $message,
            ]);

            $result = $response->json();

            Log::info('Wablas API Response', [
                'phone'    => $normalized,
                'status'   => $response->status(),
                'response' => $result,
            ]);

            if (isset($result['status']) && $result['status'] === true) {
                return true;
            }

            Log::warning('Wablas API gagal', [
                'message' => $result['message'] ?? 'Unknown error',
                'data'    => $result,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Wablas Exception: ' . $e->getMessage());
            return false;
        }
    }
}
