<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Mail;
use App\Mail\SecurityAlertMail;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'severity',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime'
    ];

    public $timestamps = false; // Solo usamos created_at

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Método estático para registrar eventos de seguridad
    public static function logEvent(string $eventType, string $severity, ?int $userId, string $description, array $metadata = [])
    {
        $log = self::create([
            'event_type' => $eventType,
            'severity' => $severity,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'description' => $description,
            'metadata' => $metadata
        ]);

        // Enviar alerta por email si es crítico
        if ($severity === 'critical') {
            self::sendSecurityAlert($log);
        }

        return $log;
    }

    // Métodos helper para tipos específicos de eventos
    public static function logLoginSuccess($userId)
    {
        return self::logEvent(
            'login_success',
            'info',
            $userId,
            'Usuario inició sesión exitosamente'
        );
    }

    public static function logLoginFailed(?string $email)
    {
        return self::logEvent(
            'login_failed',
            'warning',
            null,
            'Intento de login fallido',
            ['email' => $email]
        );
    }

    public static function logUnauthorizedAccess(?int $userId, string $url)
    {
        return self::logEvent(
            'unauthorized_access',
            'warning',
            $userId,
            'Intento de acceso no autorizado',
            ['attempted_url' => $url]
        );
    }

    public static function logDataChange(int $userId, string $model, $modelId, string $action, array $changes = [])
    {
        return self::logEvent(
            'data_change',
            'info',
            $userId,
            "Cambio en {$model} #{$modelId}: {$action}",
            [
                'model' => $model,
                'model_id' => $modelId,
                'action' => $action,
                'changes' => $changes
            ]
        );
    }

    public static function logPaymentAttempt(int $userId, $solicitudId, string $gateway, $amount)
    {
        return self::logEvent(
            'payment_attempt',
            'info',
            $userId,
            "Intento de pago para solicitud #{$solicitudId}",
            [
                'solicitud_id' => $solicitudId,
                'gateway' => $gateway,
                'amount' => $amount
            ]
        );
    }

    public static function logSuspiciousActivity(?int $userId, string $description, array $metadata = [])
    {
        return self::logEvent(
            'suspicious_activity',
            'critical',
            $userId,
            $description,
            $metadata
        );
    }

    // Enviar alerta por email
    protected static function sendSecurityAlert($log)
    {
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@maxisolutions.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new SecurityAlertMail($log));
            }
        } catch (\Exception $e) {
            \Log::error('Error enviando alerta de seguridad: ' . $e->getMessage());
        }
    }

    // Scopes para filtros comunes
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    public function scopeWarning($query)
    {
        return $query->where('severity', 'warning');
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeByEventType($query, $type)
    {
        return $query->where('event_type', $type);
    }
}
