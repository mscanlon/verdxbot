<?php

namespace App\Http\Middleware;

use Closure;
use App\Team;

class TeamValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $team = Team::where(
            'slack_team_id',
            $request->input('team_id')
        )->first();
        if ($team) {
            return $next($request);
        } else {
            return response('Not Authorized', 401);
        }

    }
}
