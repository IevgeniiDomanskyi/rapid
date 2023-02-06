<?php

namespace App\Http\Repositories;

use App\PaymentHistory;

class PaymentHistoryRepository
{
    public function create(array $data)
    {
        return PaymentHistory::create($data);
    }

    public function all()
    {
        return PaymentHistory::all();
    }
}
