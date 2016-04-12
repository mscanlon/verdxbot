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
        $msgType = "in_channel";
        $message = "";

        $text = strtolower($request->input('text'));
        if ($text == "count") {
            $msgType = "ephemeral";
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
            "response_type" => $msgType,
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


        preg_match_all('/@\w+/',$request->input('text'),$userNames);

        if(!in_array('@'.$giver->user_name, $userNames[0])) {
            foreach($userNames[0] as $userName) {
                $text = trim(substr($userName,1));
                $winner = Member::where('team_id', $team->id)
                    ->where('user_name', $text)->first();
                if (is_null($winner)) {
                    $winner = new Member(['user_name' => $text]);
                    $team->members()->save($winner);
                }

                $winner->trophies()->create(['giver' => $giver->id]);
            }
            return "@".$giver->user_name . " gave " . implode(" ", $userNames[0]) . " a trophy!";

        } else {
            return "You can't give yourself a trophy!";
        }



    }
}