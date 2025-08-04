<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        
        // Debug logging
        \Log::info('RoleMiddleware Debug', [
            'user_id' => auth()->id(),
            'user_role' => $userRole,
            'required_roles' => $roles,
            'url' => $request->url()
        ]);
        
        if (!in_array($userRole, $roles)) {
            abort(403, "Akses ditolak! Role Anda: {$userRole}. Role yang dibutuhkan: " . implode(', ', $roles));
        }

        return $next($request);
    }
}
