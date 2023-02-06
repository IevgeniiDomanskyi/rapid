<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'type', 'coach_id', 'region_id', 'from', 'to', 'riders', 'price', 'fee'];

    protected $dates = [
        'from', 'to',
    ];

    /**Start Relations */
    public function region()
    {
        return $this->belongsTo('App\Region');
    }

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
        return $this->belongsToMany('App\User')->withPivot('card_id', 'plan', 'instalment', 'is_paid', 'is_bank')->withTimestamps();
    }
    /**End Relations */

    /**Start Helpers */
    public function eventId()
    {
        return ($this->type == 'event' ? 'EV' : 'RO').(101 + $this->id);
    }
    /**End Helpers */
}
