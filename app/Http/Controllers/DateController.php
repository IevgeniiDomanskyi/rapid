<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Date\Save as DateSaveRequest;
use App\Http\Resources\Date\Item as DateItemResource;
use App\User;

class DateController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request, User $user)
    {
        $result = service('Date')->all($user);
        return response()->result(DateItemResource::collection($result));
    }

    public function save(DateSaveRequest $request, User $user)
    {
        $input = $request->only('dates');

        $result = service('Date')->save($user, $input);
        $result = service('Date')->all($user);
        return response()->result(DateItemResource::collection($result));
    }
}
