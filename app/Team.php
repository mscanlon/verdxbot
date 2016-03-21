<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slack_team_id', 'token',
    ];

    /**
     * Scope a query to find based on supplied token and slack_team_id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTeamTokenCheck($query, $slack_team_id, $token)
    {
        return $query->where([
            ['slack_team_id', $slack_team_id],
            ['token', $token]
        ]);
    }
}