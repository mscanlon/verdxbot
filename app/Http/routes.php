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

$app->get('{path:.*}', function () use ($app) 
{
    return "Verdx Bot is coming soon :) ". date('Y-m-d H:i:s');
    //return $app->version()."verdx bot";
});

$app->post('trophy','TrophyController@parse');
