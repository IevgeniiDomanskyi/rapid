<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Resources\Dialog\Item as DialogItemResource;
use App\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function read(Request $request, Message $message)
    {
        $result = service('Message')->read($message);
        return response()->result(true);
    }
}
