<?php

namespace App\Http\Repositories;

use App\Payment;
use App\User;

class PaymentRepository
{
    public function get($id)
    {
        return Payment::find($id);
    }
    
    public function all()
    {
        return Payment::all();
    }

    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data)
    {
        $payment->fill($data);
        $payment->save();
        return $payment;
    }

    public function attach(Payment $payment, User $user)
    {
        $payment->user()->associate($user);
        $payment->save();
        return $payment;
    }
}
