<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dialog\Item as DialogItemResource;
use App\Dialog;
use App\Book;
use App\Message;
use App\User;

class DialogController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request, User $user)
    {
        $result = service('Dialog')->all($user);
        return response()->result(DialogItemResource::collection($result));
    }

    public function get(Request $request, Dialog $dialog)
    {
        $result = service('Dialog')->get($dialog->id);
        return response()->result(new DialogItemResource($result));
    }

    public function byBook(Request $request, Book $book)
    {
        $result = service('Dialog')->byBook($book);
        return response()->result(DialogItemResource::collection($result));
    }

    public function call(Request $request, Dialog $dialog)
    {
        $input = $request->only('message');

        $result = service('Dialog')->call($dialog, $input);
        return response()->result(true, 'Call was saved');
    }

    public function notes(Request $request, Dialog $dialog)
    {
        $input = $request->only('message');

        $result = service('Dialog')->notes($dialog, $input);
        return response()->result(true, 'Note was saved');
    }

    public function message(Request $request, Dialog $dialog)
    {
        $input = $request->only('message');

        $result = service('Dialog')->message($dialog, $input);
        $result = service('Dialog')->get($dialog->id);
        return response()->result(new DialogItemResource($result), 'Message was saved');
    }

    public function close(Request $request, Dialog $dialog)
    {
        $result = service('Dialog')->update($dialog, [
            'status' => 1,
        ]);
        $result = service('Dialog')->get($dialog->id);
        return response()->result(new DialogItemResource($result), 'Conversation was closed');
    }
}
