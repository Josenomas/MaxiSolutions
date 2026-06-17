<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CredencialesTemporalesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $passwordTemporal;

    public function __construct(User $user, $passwordTemporal)
    {
        $this->user = $user;
        $this->passwordTemporal = $passwordTemporal;
    }

    public function envelope()
    {
        return new Envelope(
            from: 'cuentas@maxisolutions.cl',
            subject: 'Bienvenido a MaxiSolutions - Credenciales de Acceso',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.credenciales-temporales',
        );
    }

    public function attachments()
    {
        return [];
    }
}
