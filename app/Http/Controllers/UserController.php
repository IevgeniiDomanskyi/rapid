<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CustomerSave as UserCustomerSaveRequest;
use App\Http\Requests\User\CardConnect as UserCardConnectRequest;
use App\Http\Resources\User\Customer as UserCustomerResource;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function customers(Request $request)
    {
        $result = service('User')->customers();
        return response()->result(UserCustomerResource::collection($result));
    }

    public function customersSave(UserCustomerSaveRequest $request)
    {
        $input = $request->only(['firstname', 'lastname', 'email', 'phone', 'postcode', 'line1', 'line2', 'town', 'county', 'country']);
        $result = service('User')->customersSave($input);
        $result = service('User')->customers();

        return response()->result(UserCustomerResource::collection($result));
    }

    public function customersUpdate(UserCustomerSaveRequest $request, User $customer)
    {
        $input = $request->only(['firstname', 'lastname', 'email', 'phone', 'postcode', 'line1', 'line2', 'town', 'county', 'country']);
        $result = service('User')->customersUpdate($customer, $input);
        $result = service('User')->customers();

        return response()->result(UserCustomerResource::collection($result));
    }

    public function customersGet(Request $request, User $customer)
    {
        $result = service('User')->get($customer->id);
        return response()->result(new UserCustomerResource($result));
    }

    public function import()
    {
        $result = service('User')->import();
        return response()->result($result, 'Customers were imported successfully');
    }

    public function requestPaymentDetails(Request $request, User $user)
    {
        $result = service('User')->requestPaymentDetails($user);
        return response()->result($result);
    }

    public function cardConnect(UserCardConnectRequest $request)
    {
        $input = $request->only(['id', 'type', 'card']);
        $result = service('User')->cardConnect($input);

        return response()->result($result, 'Card was successfully added');
    }
}
