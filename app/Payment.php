<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'book_id', 'amount', 'instalment', 'status', 'order_id', 'auth_code', 'transaction_id', 'scheme_id', 'due_date'];

    protected $dates = [
        'due_date',
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
    /**End Relations */
}
