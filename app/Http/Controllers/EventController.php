<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\Temp as EventTempRequest;
use App\Http\Requests\Event\Save as EventSaveRequest;
use App\Http\Requests\Event\Pay as EventPayRequest;
use App\Http\Resources\Event\Item as EventItemResource;
use App\Http\Resources\Event\Customer as EventCustomerResource;
use App\Http\Resources\Event\Temp as EventTempResource;
use App\Http\Resources\Event\BookingItem as EventBookingItemResource;
use App\Http\Resources\Event\Enquiry as EventEnquiryResource;
use App\Event;

class EventController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function tempGet(Request $request, $token)
    {
        $result = service('Event')->tempGet($token);

        return response()->result($result ? new EventTempResource($result) : $result);
    }

    public function temp(EventTempRequest $request)
    {
        $input = $request->only(['token', 'step', 'max', 'options']);
        $result = service('Event')->temp($input);

        return response()->result(new EventTempResource($result));
    }

    public function all(Request $request)
    {
        $result = service('Event')->all();
        return response()->result(EventItemResource::collection($result));
    }

    public function get(Request $request, Event $event)
    {
        $result = service('Event')->get($event->id);

        return response()->result(new EventItemResource($result));
    }

    public function save(EventSaveRequest $request)
    {
        $input = $request->only(['name', 'type', 'from', 'to', 'regions', 'riders', 'price', 'coach', 'fee']);

        $result = service('Event')->save($input);
        $result = service('Event')->all();
        return response()->result(EventItemResource::collection($result), 'Event was successfully created');
    }

    public function update(EventSaveRequest $request, Event $event)
    {
        $input = $request->only(['name', 'type', 'from', 'to', 'regions', 'riders', 'price', 'coach', 'fee']);

        $result = service('Event')->update($event, $input);
        $result = service('Event')->all();
        return response()->result(EventItemResource::collection($result), 'Event was successfully updated');
    }

    public function book(Request $request, Event $event)
    {
        $input = $request->only(['token', 'options']);

        $result = service('Event')->book($event, $input);
        return response()->result($result, 'Thank you for your order');
    }

    public function bookCreate(Request $request)
    {
        $input = $request->only(['customer', 'card', 'event', 'plan', 'instalment', 'request']);
        $result = service('Event')->bookCreate($input);
        $result = service('Event')->all();

        return response()->result(EventItemResource::collection($result), 'Event was successfully booked');
    }

    public function customers(Request $request, Event $event)
    {
        $result = service('Event')->customers($event);
        return response()->result(EventCustomerResource::collection($result));
    }

    public function remove(Request $request, Event $event)
    {
        $result = service('Event')->remove($event);
        $result = service('Event')->all();
        return response()->result(EventItemResource::collection($result), 'Event was successfully removed');
    }

    public function bookings(Request $request)
    {
        $result = service('Event')->bookings();
        return response()->result(EventBookingItemResource::collection($result));
    }

    public function pay(EventPayRequest $request)
    {
        $input = $request->only(['id', 'bank']);

        $result = service('Event')->pay($input);
        $result = service('Event')->bookings();
        return response()->result(EventBookingItemResource::collection($result), 'This booking was paid');
    }

    public function enquiry(Request $request)
    {
        $input = $request->only(['token']);
        $result = service('Event')->enquiry($input);

        return response()->result($result, 'The Enquiry was sent');
    }

    public function enquiryAll(Request $request)
    {
        $result = service('Event')->enquiryAll();

        return response()->result(EventEnquiryResource::collection($result));
    }
}
