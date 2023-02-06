<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'email', 'password', 'firstname', 'lastname', 'phone', 'dob', 'postcode', 'gp_key',
        'is_active', 'gdpr', 'is_notified', 'payment_request', 'auth_token', 'activity_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'dob', 'activity_at',
    ];

    /**Start Relations */
    public function hashes()
    {
        return $this->morphToMany('App\Hash', 'hashable')->withTimestamps();
    }

    public function exp()
    {
        return $this->belongsTo('App\Exp');
    }

    public function address()
    {
        return $this->belongsTo('App\Address');
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }

    public function coachBooks()
    {
        return $this->hasMany('App\Book', 'coach_id');
    }

    public function enquiries()
    {
        return $this->hasMany('App\Enquiry');
    }

    public function postcodes()
    {
        return $this->belongsToMany('App\Postcode');
    }

    public function regions()
    {
        return $this->belongsToMany('App\Region');
    }

    public function docs()
    {
        return $this->hasMany('App\Doc');
    }

    public function documents()
    {
        return $this->hasMany('App\Document');
    }

    public function dates()
    {
        return $this->hasMany('App\Date', 'coach_id');
    }

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function paymentHistory()
    {
        return $this->hasMany('App\PaymentHistory');
    }

    public function cardRequests()
    {
        return $this->hasMany('App\CardRequest');
    }

    public function vouchers()
    {
        return $this->belongsTomany('App\Voucher');
    }

    public function events()
    {
        return $this->belongsTomany('App\Event')->withPivot('card_id', 'plan', 'instalment', 'is_paid', 'is_bank')->withTimestamps();
    }

    public function trackDays()
    {
        return $this->belongsTomany('App\TrackDay')->withPivot('card_id', 'level', 'plan', 'instalment', 'is_paid', 'is_bank')->withTimestamps();
    }
    /**End Relations */
}
