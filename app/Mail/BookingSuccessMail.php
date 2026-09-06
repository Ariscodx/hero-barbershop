<?php

namespace App\Mail;

use App\Models\KuotaBooking;
use App\Models\Pelanggan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public KuotaBooking $kuota;
    public Pelanggan $pelanggan;

    /**
     * Create a new message instance.
     */
    public function __construct(KuotaBooking $kuota, Pelanggan $pelanggan)
    {
        $this->kuota = $kuota;
        $this->pelanggan = $pelanggan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Booking Berhasil - Hero Barbershop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-success',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
