<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\PaymentRepository;
use App\Events\Payment\Completed as PaymentCompleted;
use App\Payment;
use App\User;

class PaymentService extends Service
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function all()
    {
        return $this->paymentRepository->all();
    }

    public function get(int $id)
    {
        return $this->paymentRepository->get($id);
    }

    public function create(User $user, array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $payment = $this->paymentRepository->create($input);
            $payment = $this->paymentRepository->attach($payment, $user);

            return $payment;
        }

        return response()->message('You haven\'t access to create payment', 'error', 403);
    }

    public function pay(User $user, Payment $payment, array $input)
    {
        $me = auth()->user();

        if ($me->role == 0 && $me->id == $user->id) {
            $card = service('Card')->get($input['card']);
            $response = service('Card')->charge($user, $card, [
                'amount' => $payment->amount,
                'instalment' => $payment->instalment,
                'cvv' => $input['cvv'],
            ]);
            
            if ($response) {
                $payment = $this->paymentRepository->update($payment, $response);

                event(new PaymentCompleted($payment->book));

                return $payment;
            }

            return false;
        }

        return response()->message('You haven\'t access to make payment', 'error', 403);
    }
}
