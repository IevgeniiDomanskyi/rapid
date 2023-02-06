<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    protected $fillable = ['token', 'step', 'max', 'options'];

    protected $casts = [
        'options' => 'array'
    ];
}
