<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictInstructorFromUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'instructor') {
            // If trying to access any dashboard route except profile
            if ($request->is('dashboard/users')) {
                return abort(403, 'access denied!');
            }
            if ($request->is('dashboard')) {
                return redirect()->route('courses.all');
            }
        }

        return $next($request);
    }
}
