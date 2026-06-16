<?php

namespace App\Mail;

use App\Models\Solicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaSolicitudNotificacion extends Mailable
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
            subject: 'Nueva Solicitud de Cotizacion - MaxiSolutions',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.nueva-solicitud-admin',
        );
    }

    public function attachments()
    {
        return [];
    }
}
