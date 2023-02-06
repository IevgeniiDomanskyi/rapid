<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrackDay\Temp as TrackDayTempRequest;
use App\Http\Requests\TrackDay\Save as TrackDaySaveRequest;
use App\Http\Requests\TrackDay\Pay as TrackDayPayRequest;
use App\Http\Resources\TrackDay\Item as TrackDayItemResource;
use App\Http\Resources\TrackDay\Customer as TrackDayCustomerResource;
use App\Http\Resources\TrackDay\Temp as TrackDayTempResource;
use App\Http\Resources\TrackDay\BookingItem as TrackDayBookingItemResource;
use App\Http\Resources\TrackDay\Enquiry as TrackDayEnquiryResource;
use App\TrackDay;

class TrackDayController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function tempGet(Request $request, $token)
    {
        $result = service('TrackDay')->tempGet($token);

        return response()->result($result ? new TrackDayTempResource($result) : $result);
    }

    public function temp(TrackDayTempRequest $request)
    {
        $input = $request->only(['token', 'step', 'max', 'options']);
        $result = service('TrackDay')->temp($input);

        return response()->result(new TrackDayTempResource($result));
    }

    public function all(Request $request)
    {
        $result = service('TrackDay')->all();
        return response()->result(TrackDayItemResource::collection($result));
    }

    public function get(Request $request, TrackDay $trackDay)
    {
        $result = service('TrackDay')->get($trackDay->id);

        return response()->result(new TrackDayItemResource($result));
    }

    public function save(TrackDaySaveRequest $request)
    {
        $input = $request->only(['name', 'trackDate', 'riders1', 'riders2', 'riders3', 'price', 'coach', 'fee']);

        $result = service('TrackDay')->save($input);
        $result = service('TrackDay')->all();
        return response()->result(TrackDayItemResource::collection($result), 'Track Day was successfully created');
    }

    public function update(TrackDaySaveRequest $request, TrackDay $trackDay)
    {
        $input = $request->only(['name', 'trackDate', 'riders1', 'riders2', 'riders3', 'price', 'coach', 'fee']);

        $result = service('TrackDay')->update($trackDay, $input);
        $result = service('TrackDay')->all();
        return response()->result(TrackDayItemResource::collection($result), 'Track Day was successfully updated');
    }

    public function book(Request $request, TrackDay $trackDay)
    {
        $input = $request->only(['token', 'options']);

        $result = service('TrackDay')->book($trackDay, $input);
        return response()->result($result, 'Thank you for your order');
    }

    public function bookCreate(Request $request)
    {
        $input = $request->only(['customer', 'card', 'trackDay', 'plan', 'instalment', 'request']);
        $result = service('TrackDay')->bookCreate($input);
        $result = service('TrackDay')->all();

        return response()->result(TrackDayItemResource::collection($result), 'Track Day was successfully booked');
    }

    public function customers(Request $request, TrackDay $trackDay)
    {
        $result = service('TrackDay')->customers($trackDay);
        return response()->result(TrackDayCustomerResource::collection($result));
    }

    public function remove(Request $request, TrackDay $trackDay)
    {
        $result = service('TrackDay')->remove($trackDay);
        $result = service('TrackDay')->all();
        return response()->result(TrackDayItemResource::collection($result), 'Track Day was successfully removed');
    }

    public function bookings(Request $request)
    {
        $result = service('TrackDay')->bookings();
        return response()->result(TrackDayBookingItemResource::collection($result));
    }

    public function pay(TrackDayPayRequest $request)
    {
        $input = $request->only(['id', 'bank']);

        $result = service('TrackDay')->pay($input);
        $result = service('TrackDay')->bookings();
        return response()->result(TrackDayBookingItemResource::collection($result), 'This booking was paid');
    }

    public function enquiry(Request $request)
    {
        $input = $request->only(['token']);
        $result = service('TrackDay')->enquiry($input);

        return response()->result($result, 'The Enquiry was sent');
    }

    public function enquiryAll(Request $request)
    {
        $result = service('TrackDay')->enquiryAll();

        return response()->result(TrackDayEnquiryResource::collection($result));
    }
}
