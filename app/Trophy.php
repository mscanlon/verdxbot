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



}