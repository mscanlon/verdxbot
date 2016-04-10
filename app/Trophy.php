<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Trophy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trophies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'giver', 'winner',
    ];

    public static function scoreboard($team_id)
    {
        return DB::table('trophies')
            ->select(DB::raw('winner, count(*) as score'))
            ->where('team_id', $team_id)
            ->groupBy('winner')
            ->orderBy('score', 'desc')
            ->get();
    }

}