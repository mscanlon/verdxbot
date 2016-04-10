<?php

namespace App\Http\Controllers;

use App\Team;
use App\Trophy;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TrophyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('IsTeam');
    }

    public function parse(Request $request)
    {

        $text = strtolower($request->input('text'));
        if ($text == "count") {
            $team = Team::where('slack_team_id',$request->input('team_id'))->first();
            $scores = Trophy::scoreboard($team->id);
            $scoreStr = "";
            foreach ( $scores as $score){
                $scoreStr .= $score->winner . ": " . $score->score . "\n";
            }
            return $scoreStr;
        } elseif (substr($text, 0, 1) == "@") {
            return $this->giveTrophy($request);
        } else{
            return "You can't do anything right. Try again!";
        }
    }

    protected function giveTrophy(Request $request)
    {
        $giver = "@".$request->input('user_name');
        $text = trim($request->input('text'));
        if ($giver != $text){
            $team = Team::where('slack_team_id',$request->input('team_id'))->first();
            $trophy = new Trophy(['giver' => $giver, 'winner' => $text]);
            $team->trophies()->save($trophy);
            return $giver . " gave " . $text . " a trophy!";
        } else {
            return "YOU CAN'T GIVE YOURSELF A TROPHY!!!";
        }

    }
}