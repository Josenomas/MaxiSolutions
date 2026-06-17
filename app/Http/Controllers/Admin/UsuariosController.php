<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\CredencialesTemporalesMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tipo_usuario' => 'required|in:cliente,admin',
        ]);

        // Generar contraseña temporal aleatoria
        $passwordTemporal = Str::random(12);

        // Crear usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'tipo_usuario' => $validated['tipo_usuario'],
            'password' => Hash::make($passwordTemporal),
            'debe_cambiar_password' => true,
            'email_verified_at' => now(), // Auto-verificar email
        ]);

        // Enviar email con credenciales
        try {
            Mail::to($user->email)->send(new CredencialesTemporalesMail($user, $passwordTemporal));

            return redirect()->route('admin.usuarios.index')
                ->with('success', "Usuario creado exitosamente. Se ha enviado un email a {$user->email} con las credenciales de acceso.");
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios.index')
                ->with('warning', "Usuario creado, pero hubo un error al enviar el email. Contraseña temporal: {$passwordTemporal}");
        }
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'tipo_usuario' => 'required|in:cliente,admin',
        ]);

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $usuario)
    {
        // No permitir eliminar el propio usuario
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    public function resetPassword(User $usuario)
    {
        // Generar nueva contraseña temporal
        $passwordTemporal = Str::random(12);

        $usuario->update([
            'password' => Hash::make($passwordTemporal),
            'debe_cambiar_password' => true,
        ]);

        // Enviar email
        try {
            Mail::to($usuario->email)->send(new CredencialesTemporalesMail($usuario, $passwordTemporal));

            return redirect()->route('admin.usuarios.index')
                ->with('success', "Contraseña restablecida. Se ha enviado un email a {$usuario->email}.");
        } catch (\Exception $e) {
            return redirect()->route('admin.usuarios.index')
                ->with('warning', "Contraseña restablecida, pero hubo un error al enviar el email. Nueva contraseña: {$passwordTemporal}");
        }
    }
}
