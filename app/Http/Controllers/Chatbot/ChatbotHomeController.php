<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;

class ChatbotHomeController extends Controller
{
    public function landing()
    {
        return view('chatbot.landing', [
            'productos' => []
        ]);
    }
}
