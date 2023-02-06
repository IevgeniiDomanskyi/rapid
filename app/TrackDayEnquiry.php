<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackDayEnquiry extends Model
{
    protected $fillable = ['track_day_id', 'message', 'is_guest'];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function trackDay()
    {
        return $this->belongsTo('App\TrackDay');
    }

    /* public function dialog()
    {
        return $this->hasOne('App\Dialog');
    } */
    /**End Relations */
}
