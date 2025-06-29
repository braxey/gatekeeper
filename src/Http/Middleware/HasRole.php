<?php

namespace Gillyware\Gatekeeper\Http\Middleware;

use Closure;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Illuminate\Http\Request;

class HasRole
{
    public function handle(Request $request, Closure $next, string $roleName)
    {
        $user = $request->user();

        if (! Gatekeeper::modelHasRole($user, $roleName)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
