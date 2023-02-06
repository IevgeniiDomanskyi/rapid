<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = ['user_id', 'book_id', 'event_id', 'amount', 'paid_at'];

    protected $dates = [
        'paid_at',
    ];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    /**End Relations */
}
