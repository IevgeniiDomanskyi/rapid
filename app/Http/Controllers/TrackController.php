<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Track\Save as TrackSaveRequest;
use App\Http\Resources\Track\Item as TrackItemResource;
use App\Http\Requests\Track\Date as TrackDateRequest;
use App\Http\Resources\Track\Date as TrackDateResource;
use App\TrackDate;

class TrackController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Track')->all();
        return response()->result(TrackItemResource::collection($result));
    }

    public function save(TrackSaveRequest $request)
    {
        $input = $request->only(['name', 'region', 'email']);

        $result = service('Track')->save($input);
        $result = service('Track')->all();
        return response()->result(TrackItemResource::collection($result), 'Track was successfully created');
    }

    public function allDate(Request $request)
    {
        $result = service('Track')->allDate();
        return response()->result(TrackDateResource::collection($result));
    }

    public function saveDate(TrackDateRequest $request)
    {
        $input = $request->only(['date', 'track', 'riders']);

        $result = service('Track')->saveDate($input);
        $result = service('Track')->allDate();
        return response()->result(TrackDateResource::collection($result), 'Track date was successfully created');
    }

    public function removeDate(Request $request, TrackDate $track_date)
    {
        $result = service('Track')->removeDate($track_date);
        $result = service('Track')->allDate();
        return response()->result(TrackDateResource::collection($result), 'Track date was successfully removed');
    }

    public function dateByRegion(Request $request)
    {
        $input = $request->only(['region']);

        $result = service('Track')->dateByRegion($input);
        return response()->result(TrackDateResource::collection($result));
    }
}
