<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name'
    ];
    

    public function trophies()
    {
        return $this->hasMany(Trophy::class);
    }
}