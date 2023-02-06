<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Address\Save as AddressSaveRequest;
use App\Http\Resources\Address\Item as AddressItemResource;
use App\User;

class AddressController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function get(Request $request, User $user)
    {
        $result = service('Address')->get($user);

        return response()->result($result ? new AddressItemResource($result) : $result);
    }

    public function save(AddressSaveRequest $request, User $user)
    {
        $input = $request->only(['line1', 'line2', 'town', 'city', 'county', 'country', 'postcode']);
        $result = service('Address')->save($user, $input);

        return response()->result(new AddressItemResource($result), 'Address was successfully saved');
    }
}
