<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSchoolContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->isSuperAdmin()) {
                if ($request->has('school_id')) {
                    session(['school_id' => $request->input('school_id')]);
                }
            } elseif ($user->school_id) {
                session(['school_id' => $user->school_id]);
            }
        }

        return $next($request);
    }
}
