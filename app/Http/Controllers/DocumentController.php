<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Document\Item as DocumentItemResource;
use App\Document;
use App\User;

class DocumentController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request, User $user)
    {
        $result = service('Document')->all($user);

        return response()->result(DocumentItemResource::collection($result));
    }

    public function sign(Request $request, User $user, Document $document)
    {
        $result = service('Document')->sign($user, $document);
        $result = service('Document')->all($user);

        return response()->result(DocumentItemResource::collection($result), 'Document was successfully signed');
    }
}
