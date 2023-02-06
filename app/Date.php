<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coach_id', 'date',
    ];

    protected $dates = ['date'];

    /**Start Relations */
    public function coach()
    {
        return $this->belongsTo('App\User', 'coach_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function books()
    {
        return $this->belongsToMany('App\Book')->withPivot('road', 'slot');
    }
    /**End Relations */
}
