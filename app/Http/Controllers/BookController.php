<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\Temp as BookTempRequest;
use App\Http\Requests\Book\Assign as BookAssignRequest;
use App\Http\Requests\Book\TrackDateDefine as BookTrackDateDefineRequest;
use App\Http\Requests\Book\TrackPay as BookTrackPayRequest;
use App\Http\Requests\Book\RoadDateDefine as BookRoadDateDefineRequest;
use App\Http\Requests\Book\Only as BookOnlyRequest;
use App\Http\Requests\Book\Pay as BookPayRequest;
use App\Http\Requests\Book\Create as BookCreateRequest;
use App\Http\Resources\Book\Temp as BookTempResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\Book\Enquiry as BookEnquiryResource;
use App\Book;

class BookController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function tempGet(Request $request, $token)
    {
        $result = service('Book')->tempGet($token);

        return response()->result($result ? new BookTempResource($result) : $result);
    }

    public function temp(BookTempRequest $request)
    {
        $input = $request->only(['token', 'step', 'max', 'options']);
        $result = service('Book')->temp($input);

        return response()->result(new BookTempResource($result));
    }

    public function save(Request $request)
    {
        $input = $request->only(['token']);
        $result = service('Book')->save($input);

        return response()->result($result);
    }

    public function enquiry(Request $request)
    {
        $input = $request->only(['token']);
        $result = service('Book')->enquiry($input);

        return response()->result($result);
    }

    public function enquiryAll(Request $request)
    {
        $result = service('Book')->enquiryAll();

        return response()->result(BookEnquiryResource::collection($result));
    }

    public function all(Request $request)
    {
        $result = service('Book')->all();

        return response()->result(BookItemResource::collection($result));
    }

    public function get(Request $request, Book $book)
    {
        $result = service('Book')->get($book->id);

        return response()->result(new BookItemResource($result));
    }

    public function assign(BookAssignRequest $request)
    {
        $input = $request->only(['book', 'coach', 'fee']);

        $result = service('Book')->assign($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Coach was successfully assign');
    }

    public function trackDateDefine(BookTrackDateDefineRequest $request)
    {
        $input = $request->only(['book', 'date']);

        $result = service('Book')->trackDateDefine($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Track Date was successfully defined');
    }

    public function trackPay(BookTrackPayRequest $request)
    {
        $input = $request->only(['book', 'pay']);

        $result = service('Book')->trackPay($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Track was successfully confirmed');
    }

    public function roadDateDefine(BookRoadDateDefineRequest $request)
    {
        $input = $request->only(['book', 'date', 'road', 'slot']);

        $result = service('Book')->roadDateDefine($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Road Date was successfully defined');
    }

    public function agreeRoadDates(BookOnlyRequest $request)
    {
        $input = $request->only(['book']);

        $result = service('Book')->agreeRoadDates($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Road Dates was successfully saved');
    }

    public function requestPayment(BookOnlyRequest $request)
    {
        $input = $request->only(['book']);

        $result = service('Book')->requestPayment($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'Payment Request was successfully sent');
    }

    public function pay(BookPayRequest $request)
    {
        $input = $request->only(['id', 'bank']);

        $result = service('Book')->pay($input);
        $result = service('Book')->all();
        return response()->result(BookItemResource::collection($result), 'This booking was paid');
    }

    public function forPay(Request $request)
    {
        $result = service('Book')->forPay();
        return response()->result(BookItemResource::collection($result));
    }

    public function create(BookCreateRequest $request)
    {
        $input = $request->only(['customer', 'card','region', 'course', 'level', 'plan', 'instalment', 'request', 'voucher']);
        $result = service('Book')->create($input);
        $result = service('Book')->all();

        return response()->result(BookItemResource::collection($result), 'Booking was successfully created');
    }
}
