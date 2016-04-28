<?php

namespace App\Http\Controllers;

use App\User;
use App\Member;
use Illuminate\Http\Request;

class TrophyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function parse(Request $request)
    {
        $msgType = "in_channel";
        $message = "";

        $text = strtolower($request->input('text'));
        if ($text == "count") {
            $msgType = "ephemeral";
            $user = $request->user();
            foreach ($user->members as $member) {
                $message .= "@".$member->user_name . ": " .$member->trophies->count(). "\n";
            }
        } elseif (strpos($text,"@") !== false) {
            $message = $this->giveTrophy($request);
        } elseif (strpos(strtolower($text), "wake") !== false){
            $message = "Fuck you! I'm awake!";
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
        $user = $request->user();
        $giver = Member::where('user_id', $user->id)
            ->where('user_name', $request->input('user_name'))->first();
        if(is_null($giver)) {
            $giver = new Member(['user_name' => $request->input('user_name')]);
            $user->members()->save($giver);
        }


        preg_match_all('/@(\w|-|\.)+/',$request->input('text'),$userNames);

        if(!in_array('@'.$giver->user_name, $userNames[0])) {
            foreach($userNames[0] as $userName) {
                $text = trim(substr($userName,1));
                $winner = Member::where('user_id', $user->id)
                    ->where('user_name', $text)->first();
                if (is_null($winner)) {
                    $winner = new Member(['user_name' => $text]);
                    $user->members()->save($winner);
                }

                $winner->trophies()->create(['giver' => $giver->id]);
            }
            return "@".$giver->user_name . " gave " . implode(" ", $userNames[0]) . " a trophy!";

        } else {
            return "You can't give yourself a trophy!";
        }



    }
}