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
        $team = Team::teamTokenCheck($request->input('team_id'),
            $request->input('token'))->first();
        if ($team) {
            return $next($request);
        } else {
            return response('Your slack team '.$request->input('team_id').' is not authorized to use this!', 401);
        }

    }
}
