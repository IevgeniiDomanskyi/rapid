<?php

namespace App\Http\Repositories;

use App\Exp;

class ExpRepository
{
    public function get($id)
    {
        return Exp::find($id);
    }
    
    public function all()
    {
        return Exp::orderBy('value')->get();
    }

    public function getByValue(int $value)
    {
        return Exp::where('value', $value)->first();
    }
}
