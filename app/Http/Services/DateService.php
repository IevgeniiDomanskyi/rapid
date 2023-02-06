<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\DateRepository;
use App\User;

class DateService extends Service
{
    protected $dateRepository;

    public function __construct(DateRepository $dateRepository)
    {
        $this->dateRepository = $dateRepository;
    }

    public function get($id)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->dateRepository->get($id);
        }

        return response()->message('You haven\'t access to get dates', 'error', 403);
    }

    public function all(User $coach)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            return $this->dateRepository->all($coach);
        }

        return response()->message('You haven\'t access to availablity dates', 'error', 403);
    }

    public function save(User $coach, array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $exists = [];
            foreach ($coach->dates as $coach_date) {
                $date = $coach_date->date->format('Y-m-d');
                if ( ! in_array($date, $input['dates'])) {
                    $this->dateRepository->remove($coach_date);
                } else {
                    $exists[] = $date;
                }
            }

            foreach ($input['dates'] as $date) {
                if ( ! in_array($date, $exists)) {
                    $obj = $this->dateRepository->create(['date' => $date]);
                    $obj = $this->dateRepository->connect($coach, $obj);
                }
            }

            return true;
        }

        return response()->message('You haven\'t access to change availablity', 'error', 403);
    }
}
