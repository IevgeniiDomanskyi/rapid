<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    protected $fillable = ['user_id', 'docable_id', 'docable_type', 'type', 'file', 'is_signed', 'date'];

    protected $dates = ['date'];

    /**Start Relations */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function docable()
    {
        return $this->morphTo();
    }
    /**End Relations */
}
