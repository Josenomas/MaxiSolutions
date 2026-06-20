<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\SecurityLog;

class LogFailedLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Failed $event)
    {
        // Ignorar logins fallidos del guard chatbot
        if ($event->guard === 'chatbot') {
            return;
        }

        $email = $event->credentials['email'] ?? null;
        SecurityLog::logLoginFailed($email);
    }
}
