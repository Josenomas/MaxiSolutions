<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitudConfirmacion extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    public function __construct(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Confirmacion de Solicitud - MaxiSolutions',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.solicitud-confirmacion-cliente',
        );
    }

    public function attachments()
    {
        return [];
    }
}
