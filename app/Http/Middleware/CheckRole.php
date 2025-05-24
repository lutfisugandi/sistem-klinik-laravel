<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user role is in allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on user role if not authorized
        return $this->redirectToRoleDashboard($user->role);
    }

    private function redirectToRoleDashboard($role)
    {
        switch ($role) {
            case 'pendaftaran':
                return redirect()->route('dashboard.pendaftaran');
            case 'dokter':
                return redirect()->route('dashboard.dokter');
            case 'perawat':
                return redirect()->route('dashboard.perawat');
            case 'apoteker':
                return redirect()->route('dashboard.apoteker');
            default:
                return redirect()->route('login');
        }
    }
}