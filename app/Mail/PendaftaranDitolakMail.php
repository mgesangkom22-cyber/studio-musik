<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class PendaftaranDitolakMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $catatan;

    public function __construct($nama, $catatan)
    {
        $this->nama = $nama;
        $this->catatan = $catatan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Pendaftaran Studio Musik UNU Yogyakarta',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran_ditolak',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}