<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Exp\Item as ExpItemResource;

class ExpController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Exp')->all();
        return response()->result(ExpItemResource::collection($result));
    }
}
