<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'exp_id', 'course_id', 'level_id', 'card_id', 'plan', 'instalment', 'fee', 'postcode_id', 'region_id', 'voucher_id',
        'coach_id', 'status', 'track_date_id', 'road', 'is_paid', 'is_bank',
    ];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function docs()
    {
        return $this->morphMany('App\Doc', 'docable');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function coach()
    {
        return $this->belongsTo('App\User', 'coach_id');
    }

    public function trackDate()
    {
        return $this->belongsTo('App\TrackDate');
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

    public function card()
    {
        return $this->belongsTo('App\Card');
    }

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
        return $this->belongsToMany('App\Date')->withPivot('road', 'slot');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function dialog()
    {
        return $this->hasOne('App\Dialog');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }
    /**End Relations */

    /**Start Helpers */
    public function orderId()
    {
        $code = '';
        if ($this->course_id == 1) {
            $code = 'BM'.$this->level->level;
        }

        if ($this->course_id == 2) {
            $code = 'RM'.$this->level->level;
        }

        if ($this->course_id == 3) {
            if ($this->level->crm_id == 'Level_b_fd') {
                $code = 'BFD';
            } else {
                $code = 'BE';
            }
        }

        return $code.'-'.str_pad((101 + $this->id), 5, '0', STR_PAD_LEFT);
    }
    /**End Helpers */
}
