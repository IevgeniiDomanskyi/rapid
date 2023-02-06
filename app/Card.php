<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card', 'gp_key',
    ];


    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**End Relations */
}
