<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = ['exp_id', 'course_id', 'level_id', 'message', 'is_guest'];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function exp()
    {
        return $this->belongsTo('App\Exp');
    }

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function level()
    {
        return $this->belongsTo('App\Level');
    }

    public function dialog()
    {
        return $this->hasOne('App\Dialog');
    }
    /**End Relations */
}
