<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Region\Item as RegionItemResource;

class RegionController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Region')->all();
        return response()->result(RegionItemResource::collection($result));
    }
}
