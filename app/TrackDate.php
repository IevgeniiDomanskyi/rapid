<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrackDate extends Model
{
    protected $fillable = ['track_id', 'date', 'course', 'riders', 'part1', 'part2'];

    protected $dates = [
        'date',
    ];

    /**Start Relations */
    public function track()
    {
        return $this->belongsTo('App\Track');
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }

    public function trackDays()
    {
        return $this->hasMany('App\TrackDay');
    }
    /**End Relations */


    /**Start Mutators */
    public function getTimestampAttribute()
    {
        $date = $this->date;
        
        return $date->timestamp;
    }

    public function getLeftAttribute()
    {
        return $this->riders - $this->books->count();
    }
    /**End Mutators */
}
