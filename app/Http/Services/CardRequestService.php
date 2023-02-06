<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\CardRequestRepository;
use App\User;
use App\CardRequest;

class CardRequestService extends Service
{
    protected $cardRequestRepository;

    public function __construct(CardRequestRepository $cardRequestRepository)
    {
        $this->cardRequestRepository = $cardRequestRepository;
    }

    public function get(int $id)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            return $this->coachRepository->get($id);
        }

        return [];
    }

    public function byPostcode(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            return $this->coachRepository->byPostcode($input['postcode']);
        }

        return [];
    }

    public function save(array $input)
    {
        $request = $this->cardRequestRepository->create($input);
        return $request;
    }

    public function byOrder($order_id)
    {
        $request = $this->cardRequestRepository->byOrder($order_id);
        return $request;
    }

    public function clear(User $user)
    {
        return $this->cardRequestRepository->clear($user);
    }
}
