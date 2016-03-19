<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('{path:.*}', function () use ($app) {
    return "verdx bot";
    //return $app->version()."verdx bot";
});

$app->post('{path:.*}', function() use ($app)
{
    //return $app->request->input('team_id');
    return App\Team::where('slack_team_id',
                            $app->request->input('team_id') )
                            ->first();
});
