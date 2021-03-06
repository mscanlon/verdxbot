<?php

namespace App\Http\Controllers;

use App\User;
use App\Member;
use App\Trophy;
use Illuminate\Http\Request;

class TrophyController extends Controller
{
    protected $trophyLimit = 2;

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
        $user = $request->user();
        if ($text == "count") {
            $msgType = "ephemeral";
            $message .= "Trophies Received|Given \n";
            $trophiesEarned = [];
            $trophiesGiven = [];
            
            foreach ($user->members as $member) {
                $trophiesEarned["@".$member->user_name] = $member->trophies->count();
                $trophiesGiven["@".$member->user_name] = Trophy::given($member->id)->count();
            }
            
            arsort($trophiesEarned);

            foreach ($trophiesEarned as $name=>$cnt){
                $message .= $name . ": " .$cnt. "|" .$trophiesGiven[$name]. "\n";
            }
            

            $giver = Member::where('user_id', $user->id)
                ->where('user_name', $request->input('user_name'))
                ->first();

            if( $giver ){
                $trophyCount = Trophy::given($giver->id)->today()->count();
            } else {
                $trophyCount = 0;
            }

            $message .= "You have given ".$trophyCount;
            if ($trophyCount == 1){
                $message .= " trophy";
            } else {
                $message .= " trophies";
            }
            $message .= " today.";


        } elseif (strpos($text,"@") !== false) {
            $message = $this->giveTrophy($request);
        } elseif (strpos(strtolower($text), "wake") !== false){
            $message = "Fuck you! I'm awake!";
        } elseif (strpos(strtolower($text), "given") !== false){
            $giver = Member::where('user_id', $user->id)
                ->where('user_name', $request->input('user_name'))
                ->first();

            if( $giver ){
                $trophyCount = Trophy::given($giver->id)->today()->count();
            } else {
                $trophyCount = 0;
            }

            $message .= "You have given ".$trophyCount;
            if ($trophyCount == 1){
                $message .= " trophy";
            } else {
                $message .= " trophies";
            }
            $message .= " today.";
        } else {
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


        $trophyCount = Trophy::given($giver->id)->today()->count();
        if ($trophyCount >= $this->trophyLimit){
            return "Hey, look at @".$giver->user_name.
                ". Moneybags is trying to give out more than ".
                $this->trophyLimit." trophies today!";
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