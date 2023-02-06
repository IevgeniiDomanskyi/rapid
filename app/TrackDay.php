<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackDay extends Model
{
    protected $fillable = ['name', 'track_date_id', 'coach_id', 'riders1', 'riders2', 'riders3', 'price', 'fee'];

    /**Start Relations */
    public function docs()
    {
        return $this->morphMany('App\Doc', 'docable');
    }

    public function coach()
    {
        return $this->belongsTo('App\User');
    }

    public function customers()
    {
        return $this->belongsToMany('App\User')->withPivot('card_id', 'level', 'plan', 'instalment', 'is_paid', 'is_bank')->withTimestamps();
    }

    public function trackDate()
    {
        return $this->belongsTo('App\TrackDate');
    }
    /**End Relations */

    /**Start Helpers */
    public function trackDayId()
    {
        return 'TD'.(101 + $this->id);
    }
    /**End Helpers */
}
