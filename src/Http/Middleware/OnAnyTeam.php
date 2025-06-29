<?php

namespace Gillyware\Gatekeeper\Http\Middleware;

use Closure;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Illuminate\Http\Request;

class OnAnyTeam
{
    public function handle(Request $request, Closure $next, ...$teamNames)
    {
        $user = $request->user();

        if (! Gatekeeper::modelOnAnyTeam($user, $teamNames)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
