<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SecurityLog::with('user')->latest('created_at');

        // Filtros
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Estadísticas
        $stats = [
            'total' => SecurityLog::count(),
            'critical' => SecurityLog::critical()->count(),
            'warning' => SecurityLog::warning()->count(),
            'recent_24h' => SecurityLog::recent(24)->count(),
            'login_failures_24h' => SecurityLog::recent(24)->byEventType('login_failed')->count(),
        ];

        return view('admin.security-logs.index', compact('logs', 'stats'));
    }

    public function show(SecurityLog $log)
    {
        $log->load('user');
        return view('admin.security-logs.show', compact('log'));
    }
}
