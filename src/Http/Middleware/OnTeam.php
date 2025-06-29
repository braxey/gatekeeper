<?php

namespace Gillyware\Gatekeeper\Http\Middleware;

use Closure;
use Gillyware\Gatekeeper\Facades\Gatekeeper;
use Illuminate\Http\Request;

class OnTeam
{
    public function handle(Request $request, Closure $next, string $teamName)
    {
        $user = $request->user();

        if (! Gatekeeper::modelOnTeam($user, $teamName)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
