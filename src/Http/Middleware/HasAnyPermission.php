<?php

namespace Gillyware\Gatekeeper\Http\Middleware;

use Closure;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Illuminate\Http\Request;

class HasAnyPermission
{
    public function handle(Request $request, Closure $next, ...$permissionNames)
    {
        $user = $request->user();

        if (! Gatekeeper::modelHasAnyPermission($user, $permissionNames)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
