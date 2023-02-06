<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['crm_id', 'title', 'description'];

    /**Start Relations */
    public function levels()
    {
        return $this->hasMany('App\Level');
    }
    /**End Relations */

    public function short()
    {
        $code = '';
        if ($this->id == 1) {
            $code = 'BM';
        }

        if ($this->id == 2) {
            $code = 'RM';
        }

        if ($this->id == 3) {
            $code = 'BE';
        }

        return $code;
    }
}
