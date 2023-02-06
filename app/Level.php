<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['crm_id', 'level', 'title', 'description', 'price', 'fee'];

    /**Start Relations */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    public function vouchers()
    {
        return $this->belongsTomany('App\Voucher');
    }
    /**End Relations */
}
