<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Postcode\Item as PostcodeItemResource;

class PostcodeController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Postcode')->all();
        return response()->result(PostcodeItemResource::collection($result));
    }

    public function available(Request $request)
    {
        $result = service('Postcode')->available();
        return response()->result(PostcodeItemResource::collection($result));
    }
}
