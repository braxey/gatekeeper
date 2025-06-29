<?php

namespace Gillyware\Gatekeeper\Http\Middleware;

use Closure;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Illuminate\Http\Request;

class HasAllRoles
{
    public function handle(Request $request, Closure $next, ...$roleNames)
    {
        $user = $request->user();

        if (! Gatekeeper::modelHasAllRoles($user, $roleNames)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
