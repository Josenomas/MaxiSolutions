<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pago;

class PagoRecibidoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;
    public $solicitud;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
        $this->solicitud = $pago->solicitud;
    }

    public function envelope()
    {
        return new Envelope(
            from: 'pagos@maxisolutions.cl',
            subject: 'Pago Recibido - Solicitud #' . $this->solicitud->id,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.pago-recibido',
        );
    }

    public function attachments()
    {
        return [];
    }
}
