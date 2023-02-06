<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'customer_id', 'coach_id', 'status',
    ];


    /**Start Relations */
    public function book()
    {
        return $this->belongsTo('App\Book');
    }

    public function enquiry()
    {
        return $this->belongsTo('App\Enquiry');
    }

    public function customer()
    {
        return $this->belongsTo('App\User', 'customer_id');
    }

    public function coach()
    {
        return $this->belongsTo('App\User', 'coach_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    /**End Relations */
}
