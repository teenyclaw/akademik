<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user->school_id) {
            abort(403, 'Akun Anda belum terhubung ke sekolah.');
        }

        return $next($request);
    }
}
