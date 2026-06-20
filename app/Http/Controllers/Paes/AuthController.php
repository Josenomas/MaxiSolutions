<?php

namespace App\Http\Controllers\Paes;

use App\Http\Controllers\Controller;
use App\Models\PaesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('paes.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('paes')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('paes.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales no coinciden.'],
        ]);
    }

    public function showRegister()
    {
        return view('paes.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:paes_users',
            'password' => 'required|min:8|confirmed',
            'rut' => 'nullable|string|max:12',
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $user = PaesUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rut' => $request->rut,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'plan' => 'gratuito',
            'activo' => true,
        ]);

        Auth::guard('paes')->login($user);

        return redirect()->route('paes.dashboard')->with('success', 'Bienvenido a PAES Prep!');
    }

    public function logout(Request $request)
    {
        Auth::guard('paes')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('paes.home');
    }
}
