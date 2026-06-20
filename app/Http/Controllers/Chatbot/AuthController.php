<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot\ChatbotUser;
use App\Models\Chatbot\Uso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('chatbot.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('chatbot')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('chatbot.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales no coinciden.'],
        ]);
    }

    public function showRegister()
    {
        return view('chatbot.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:chatbot_users',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        $user = ChatbotUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'plan' => 'gratuito',
            'activo' => true,
        ]);

        // Crear registro de uso inicial
        Uso::create([
            'user_id' => $user->id,
            'fecha' => today(),
            'mensajes_enviados' => 0,
            'tokens_usados' => 0,
        ]);

        Auth::guard('chatbot')->login($user);

        return redirect()->route('chatbot.dashboard')->with('success', 'Bienvenido a HateaChistopher!');
    }

    public function logout(Request $request)
    {
        Auth::guard('chatbot')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('chatbot.home');
    }
}
