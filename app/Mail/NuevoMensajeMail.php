<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Mensaje;

class NuevoMensajeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $remitente;

    public function __construct(Mensaje $mensaje)
    {
        $this->mensaje = $mensaje;
        $this->remitente = $mensaje->usuario;
    }

    public function envelope()
    {
        return new Envelope(
            from: 'soporte@maxisolutions.cl',
            subject: 'Nuevo Mensaje de ' . $this->remitente->name,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.nuevo-mensaje',
        );
    }

    public function attachments()
    {
        return [];
    }
}
