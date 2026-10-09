<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PendaftaranDiterimaMail extends Mailable
{
    public $nama;

    public function __construct($nama)
    {
        $this->nama = $nama;
    }

    public function build()
    {
        return $this
            ->subject('Pendaftaran Studio Musik UNU Yogyakarta')
            ->view('emails.pendaftaran_diterima');
    }
}