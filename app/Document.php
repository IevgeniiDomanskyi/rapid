<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['type', 'file', 'is_signed', 'date'];

    protected $dates = ['date'];

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
