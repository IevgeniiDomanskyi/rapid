<?php

namespace App\Http\Repositories;

use App\Region;

class RegionRepository
{
    public function get($id)
    {
        return Region::find($id);
    }
    
    public function all()
    {
        return Region::all();
    }
}
