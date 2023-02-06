<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exp extends Model
{
    protected $fillable = ['crm_id', 'value', 'title', 'description'];

    /**Start Relations */
    public function users()
    {
        return $this->hasMany('App\User');
    }
    /**End Relations */
}
