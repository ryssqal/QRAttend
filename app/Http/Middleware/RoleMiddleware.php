<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        // Check if the user is from the User model or PengurusMajlis model
        if ($user instanceof \App\Models\User) {
            if ($user->role !== $role) {
                return redirect('/')->with('error', 'You do not have permission to access this page.');
            }
        } elseif ($user instanceof \App\Models\PengurusMajlis) {
            if ($user->role !== $role) {
                return redirect('/')->with('error', 'You do not have permission to access this page.');
            }
        } else {
            return redirect('/')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
