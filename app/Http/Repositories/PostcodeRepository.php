<?php

namespace App\Http\Repositories;

use App\Postcode;

class PostcodeRepository
{
    public function get($id)
    {
        return Postcode::find($id);
    }
    
    public function all()
    {
        return Postcode::all();
    }
}
