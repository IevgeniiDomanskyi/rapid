<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Card\Save as CardSaveRequest;
use App\Http\Resources\Card\Item as CardItemResource;
use App\User;
use App\Card;

class CardController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function get(Request $request, User $user, $id)
    {
        $result = service('Card')->get($id);

        return response()->result(new CardItemResource($result));
    }

    public function all(Request $request, User $user)
    {
        $result = service('Card')->all($user);

        return response()->result(CardItemResource::collection($result));
    }

    public function save(CardSaveRequest $request, User $user)
    {
        $input = $request->only(['card', 'expire', 'name']);
        $result = service('Card')->save($user, $input);

        return response()->result(new CardItemResource($result));
    }

    public function remove(Request $request, User $user, Card $card)
    {
        $result = service('Card')->remove($user, $card);
        $result = service('Card')->all($user);
        return response()->result(CardItemResource::collection($result), 'Card was removed');
    }

    public function details(Request $request, User $user)
    {
        $result = service('Card')->details($user);
        return response()->result($result);
    }
}
