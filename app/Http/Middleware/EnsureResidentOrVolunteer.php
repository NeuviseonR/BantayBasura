<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureResidentOrVolunteer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user && in_array($user->role, ['resident', 'volunteer'], true), 403, 'Resident or volunteer access required.');

        return $next($request);
    }
}
