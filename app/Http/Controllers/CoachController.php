<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\Save as CoachSaveRequest;
use App\Http\Resources\Coach\Item as CoachItemResource;
use App\Http\Resources\User\Customer as UserCustomerResource;
use App\Http\Resources\Book\Item as BookItemResource;
use App\Http\Resources\Book\Enquiry as BookEnquiryResource;
use App\User;

class CoachController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request)
    {
        $result = service('Coach')->all();
        return response()->result(CoachItemResource::collection($result));
    }

    public function byPostcode(Request $request)
    {
        $input = $request->only(['postcode']);

        $result = service('Coach')->byPostcode($input);
        return response()->result(CoachItemResource::collection($result));
    }

    public function save(CoachSaveRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'email', 'phone', 'regions']);

        $result = service('Coach')->save($input);
        $result = service('Coach')->all();
        return response()->result(CoachItemResource::collection($result), 'Coach was successfully created');
    }

    public function password(Request $request, User $coach)
    {
        $result = service('Coach')->password($coach);
        return response()->result($result, 'Email with new password was sent to coach');
    }

    public function customers(Request $request)
    {
        $result = service('Coach')->customers();
        return response()->result(UserCustomerResource::collection($result));
    }

    public function books(Request $request)
    {
        $result = service('Coach')->books();
        return response()->result(BookItemResource::collection($result));
    }

    public function enquiries(Request $request)
    {
        $result = service('Coach')->enquiries();
        return response()->result(BookEnquiryResource::collection($result));
    }
}
