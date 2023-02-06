<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'amount', 'code', 'coupon_limit', 'user_limit', 'expired_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

    /**Start Relations */
    public function levels()
    {
        return $this->belongsTomany('App\Level');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('id', 'book_id', 'created_at')->withTimestamps();
    }

    public function books()
    {
        return $this->hasMany('App\Book');
    }
    /**End Relations */
}
