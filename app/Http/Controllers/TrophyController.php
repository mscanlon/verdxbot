<?php

namespace App\Http\Controllers;

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
        $this->middleware('IsTeam');
    }

    public function parse(Request $request)
    {
        return $request->input();
    }
}