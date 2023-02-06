<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['line1', 'line2', 'town', 'city', 'county', 'country', 'postcode'];

    /**Start Relations */
    public function user()
    {
        return $this->hasOne('App\User');
    }
    /**End Relations */
}
