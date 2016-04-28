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
        'giver', 'member_id',
    ];
    

    public function scopeGiven($query, $giver_id)
    {
        return $query->where('giver', $giver_id);
    }

    public function scopeToday($query)
    {
        return $query->where('created_at', '>=', date('Y-m-d 00:00:00'))
            ->where('created_at', '<=', date('Y-m-d 23:59:59'));
    }




}