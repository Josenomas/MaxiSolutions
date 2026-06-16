<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Solicitud;

class CambioEstadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;
    public $estadoAnterior;
    public $estadoNuevo;

    public function __construct(Solicitud $solicitud, $estadoAnterior, $estadoNuevo)
    {
        $this->solicitud = $solicitud;
        $this->estadoAnterior = $estadoAnterior;
        $this->estadoNuevo = $estadoNuevo;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Actualización de Estado de tu Solicitud #' . $this->solicitud->id,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.cambio-estado',
        );
    }

    public function attachments()
    {
        return [];
    }
}
