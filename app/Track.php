<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Track extends Model
{
    use Notifiable;

    protected $fillable = ['name', 'postcode_id', 'region_id', 'email'];

    /**Start Relations */
    public function postcode()
    {
        return $this->belongsTo('App\Postcode');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function dates()
    {
        return $this->hasMany('App\TrackDate');
    }
    /**End Relations */
}
