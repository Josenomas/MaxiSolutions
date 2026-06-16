<?php

namespace App\Mail;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Confirmacion de Pago - MaxiSolutions',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.pago-confirmado',
        );
    }

    public function attachments()
    {
        return [];
    }
}
