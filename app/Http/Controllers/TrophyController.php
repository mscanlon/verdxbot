<?php

namespace App\Http\Controllers;

use App\Member;
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

        $message = "";

        $text = strtolower($request->input('text'));
        if ($text == "count") {
            $team = Team::where('slack_team_id',$request->input('team_id'))->first();
            foreach ($team->members as $member) {
                $message .= "@".$member->user_name . ": " .$member->trophies->count(). "\n";
            }
        } elseif (substr($text, 0, 1) == "@") {
            $message = $this->giveTrophy($request);
        } else{
            $message = "You can't do anything right. Try again!";
        }

        $responseArray = [
            "response_type" => "in_channel",
            "text" => $message
        ];

        return response()->json($responseArray);
    }

    protected function giveTrophy(Request $request)
    {
        $team = Team::where('slack_team_id',$request->input('team_id'))->first();
        $giver = Member::where('team_id', $team->id)
            ->where('user_name', $request->input('user_name'))->first();
        if(is_null($giver)) {
            $giver = new Member(['user_name' => $request->input('user_name')]);
            $team->members()->save($giver);
        }

        $text = trim(substr($request->input('text'),1));

        $winner = Member::where('team_id', $team->id)
            ->where('user_name', $text)->first();
        if(is_null($winner)) {
            $winner = new Member(['user_name' => $text]);
            $team->members()->save($winner);
        }

        if ($giver->id != $winner->id){
            $winner->trophies()->create(['giver' => $giver->id]);
            return $giver->user_name . " gave " . $winner->user_name . " a trophy!";
        } else {
            return "YOU CAN'T GIVE YOURSELF A TROPHY!!!";
        }

    }
}