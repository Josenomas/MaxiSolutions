<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\SecurityLog;

class LogSuccessfulLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        // Ignorar logins del guard chatbot (usan su propia tabla chatbot_users)
        if ($event->guard === 'chatbot') {
            return;
        }

        SecurityLog::logLoginSuccess($event->user->id);
    }
}
