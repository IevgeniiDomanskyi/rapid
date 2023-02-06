<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Http\Services\Service;
use App\Http\Repositories\PaymentHistoryRepository;
use App\PaymentHistory;
use App\Book;
use App\Event;
use App\TrackDay;
use App\User;

class PaymentHistoryService extends Service
{
    protected $paymentHistoryRepository;

    public function __construct(PaymentHistoryRepository $paymentHistoryRepository)
    {
        $this->paymentHistoryRepository = $paymentHistoryRepository;
    }

    public function add(Book $book, $amount)
    {
        $date = Carbon::now();
        if ($book->plan > 0) {
            $instalment = $book->instalment;
            $amount = round($amount / $instalment);

            for ($i = 0; $i < $instalment; $i++) {
                $data = [
                    'user_id' => $book->user->id,
                    'book_id' => $book->id,
                    'amount' => $amount,
                    'paid_at' => $date,
                ];
                $this->paymentHistoryRepository->create($data);

                $date = $date->addMonth();
            }
        } else {
            $data = [
                'user_id' => $book->user->id,
                'book_id' => $book->id,
                'amount' => $amount,
                'paid_at' => $date,
            ];
            $this->paymentHistoryRepository->create($data);
        }
    }

    public function event(Event $event, User $user, $plan, $instalment, $amount)
    {
        $date = Carbon::now();
        if ($plan > 0) {
            $amount = round($amount / $instalment);

            for ($i = 0; $i < $instalment; $i++) {
                $data = [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'amount' => $amount,
                    'paid_at' => $date,
                ];
                $this->paymentHistoryRepository->create($data);

                $date = $date->addMonth();
            }
        } else {
            $data = [
                'user_id' => $user->id,
                'event_id' => $event->id,
                'amount' => $amount,
                'paid_at' => $date,
            ];
            $this->paymentHistoryRepository->create($data);
        }
    }

    public function trackDay(TrackDay $trackDay, User $user, $plan, $instalment, $amount)
    {
        $date = Carbon::now();
        if ($plan > 0) {
            $amount = round($amount / $instalment);

            for ($i = 0; $i < $instalment; $i++) {
                $data = [
                    'user_id' => $user->id,
                    'track_day_id' => $trackDay->id,
                    'amount' => $amount,
                    'paid_at' => $date,
                ];
                $this->paymentHistoryRepository->create($data);

                $date = $date->addMonth();
            }
        } else {
            $data = [
                'user_id' => $user->id,
                'track_day_id' => $trackDay->id,
                'amount' => $amount,
                'paid_at' => $date,
            ];
            $this->paymentHistoryRepository->create($data);
        }
    }

    public function all(User $user)
    {
        return $user->paymentHistory;
    }
}
