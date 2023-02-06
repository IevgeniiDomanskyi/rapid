<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'order_id',
    ];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    /**End Relations */
}
