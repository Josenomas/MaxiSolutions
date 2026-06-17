<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a MaxiSolutions</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4F46E5;">Bienvenido a MaxiSolutions</h2>
        
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        
        <p>Tu cuenta ha sido creada exitosamente. A continuación encontrarás tus credenciales de acceso:</p>
        
        <div style="background: #F3F4F6; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Contraseña temporal:</strong> <code style="background: #E5E7EB; padding: 2px 6px; border-radius: 3px;">{{ $passwordTemporal }}</code></p>
        </div>
        
        <p><strong>⚠️ Importante:</strong> Por seguridad, deberás cambiar esta contraseña la primera vez que inicies sesión.</p>
        
        <p>
            <a href="{{ url('/login') }}" style="display: inline-block; background: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-top: 10px;">
                Iniciar Sesión
            </a>
        </p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #6B7280;">
            Si no solicitaste esta cuenta, por favor ignora este email o contacta al administrador.
        </p>
    </div>
</body>
</html>
