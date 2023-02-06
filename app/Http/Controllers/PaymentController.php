<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\Pay as PaymentPayRequest;
use App\Http\Resources\Payment\Item as PaymentItemResource;
use App\Http\Resources\Payment\History as PaymentHistoryResource;
use App\User;
use App\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function all(Request $request, User $user)
    {
        $result = service('Payment')->all($user);
        return response()->result(PaymentItemResource::collection($result));
    }

    public function get(Request $request, User $user, Payment $payment)
    {
        $result = service('Payment')->get($payment->id);
        return response()->result(new PaymentItemResource($result));
    }

    public function pay(PaymentPayRequest $request, User $user, Payment $payment)
    {
        $input = $request->only(['card', 'cvv']);

        $result = service('Payment')->pay($user, $payment, $input);
        return response()->result(new PaymentItemResource($result));
    }

    public function history(Request $request, User $user)
    {
        $result = service('PaymentHistory')->all($user);
        return response()->result(PaymentHistoryResource::collection($result));
    }
}
