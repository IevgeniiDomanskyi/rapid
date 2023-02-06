<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Doc\Item as DocItemResource;
use App\Doc;
use App\User;

class DocController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request, User $user)
    {
        $result = service('Doc')->all($user);

        return response()->result(DocItemResource::collection($result));
    }

    public function sign(Request $request, User $user, Doc $doc)
    {
        $result = service('Doc')->sign($user, $doc);
        $result = service('Doc')->all($user);

        return response()->result(DocItemResource::collection($result), 'Document was successfully signed');
    }
}
