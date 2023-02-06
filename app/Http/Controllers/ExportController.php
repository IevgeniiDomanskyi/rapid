<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function generate(Request $request)
    {
        $input = $request->only(['options', 'items']);
        $result = service('Export')->generate($input['options'], $input['items']);
        return response()->result($result);
    }
}
