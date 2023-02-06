<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Course\Item as CourseItemResource;

class CourseController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Course')->all();
        return response()->result(CourseItemResource::collection($result));
    }
}
