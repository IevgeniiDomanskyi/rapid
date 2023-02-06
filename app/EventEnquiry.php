<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventEnquiry extends Model
{
    protected $fillable = ['event_id', 'message', 'is_guest'];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    /* public function dialog()
    {
        return $this->hasOne('App\Dialog');
    } */
    /**End Relations */
}
