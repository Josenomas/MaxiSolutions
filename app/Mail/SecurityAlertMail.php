<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SecurityLog;

class SecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $log;

    public function __construct(SecurityLog $log)
    {
        $this->log = $log;
    }

    public function envelope()
    {
        return new Envelope(
            from: 'seguridad@maxisolutions.cl',
            subject: 'ALERTA DE SEGURIDAD - MaxiSolutions',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.security-alert',
        );
    }

    public function attachments()
    {
        return [];
    }
}
