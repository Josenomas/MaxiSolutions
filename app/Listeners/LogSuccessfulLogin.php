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
        SecurityLog::logLoginSuccess($event->user->id);
    }
}
