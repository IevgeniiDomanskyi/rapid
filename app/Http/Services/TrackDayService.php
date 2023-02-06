<?php

namespace App\Http\Services;

use DB;
use Carbon\Carbon;
use App\Events\TrackDay\AssignCoach as TrackDayAssignCoachEvent;
use App\Events\TrackDay\Booked as TrackDayBookedEvent;
use App\Events\TrackDay\BookCreated as TrackDayBookCreatedEvent;
use App\Events\TrackDay\EnquiryCreated as TrackDayEnquiryCreatedEvent;
use App\Http\Services\Service;
use App\Http\Repositories\TrackDayRepository;
use App\TrackDay;
use App\Temp;

class TrackDayService extends Service
{
    protected $trackDayRepository;

    public function __construct(TrackDayRepository $trackDayRepository)
    {
        $this->trackDayRepository = $trackDayRepository;
    }

    public function all()
    {
        return $this->trackDayRepository->all();
    }

    public function get(int $id)
    {
        return $this->trackDayRepository->get($id);
    }

    public function tempGet(string $token)
    {
        return $this->trackDayRepository->getTemp($token);
    }

    public function temp(array $input)
    {
        $temp = $this->trackDayRepository->getTemp($input['token']);
        if ( ! $temp) {
            $temp = new Temp();
        }

        $temp = $this->trackDayRepository->updateTemp($temp, $input);

        return $temp;
    }

    public function save(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $data = [
                'track_date_id' => $input['trackDate'],
                'coach_id' => empty($input['coach']) ? 0 : $input['coach'],
                'name' => $input['name'],
                'riders1' => empty($input['riders1']) ? 0 : $input['riders1'],
                'riders2' => empty($input['riders2']) ? 0 : $input['riders2'],
                'riders3' => empty($input['riders3']) ? 0 : $input['riders3'],
                'price' => $input['price'],
                'fee' => $input['fee'],
            ];

            $trackDay = $this->trackDayRepository->create($data);

            if ( ! empty($input['coach'])) {
                $trackDay->load('coach');
                event(new TrackDayAssignCoachEvent($trackDay));
            }

            return true;
        }

        return response()->message('You haven\'t access to create track day', 'error', 403);
    }

    public function update(TrackDay $trackDay, array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $old_coach_id = ! empty($trackDay->coach->id) ? $trackDay->coach->id : 0;

            $data = [
                'track_date_id' => $input['trackDate'],
                'coach_id' => empty($input['coach']) ? 0 : $input['coach'],
                'name' => $input['name'],
                'riders1' => empty($input['riders1']) ? 0 : $input['riders1'],
                'riders2' => empty($input['riders2']) ? 0 : $input['riders2'],
                'riders3' => empty($input['riders3']) ? 0 : $input['riders3'],
                'price' => $input['price'],
                'fee' => $input['fee'],
            ];

            $trackDay = $this->trackDayRepository->update($trackDay, $data);

            if ( ! empty($input['coach']) && $old_coach_id != $input['coach']) {
                $trackDay->load('coach');
                event(new TrackDayAssignCoachEvent($trackDay));
            }

            return true;
        }

        return response()->message('You haven\'t access to update track day', 'error', 403);
    }

    public function customers(TrackDay $trackDay)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            return $trackDay->customers;
        }

        return [];
    }

    public function book(TrackDay $trackDay, array $input)
    {
        $me = auth()->user();

        $temp = $this->trackDayRepository->getTemp($input['token']);
        if ($temp) {
            $trackDay = $this->trackDayRepository->connect($trackDay, $me, [
                'card_id' => ! empty($temp->options['card']) ? $temp->options['card'] : 0,
                'plan' => ! empty($temp->options['plan']) ? $temp->options['plan'] : 0,
                'instalment' => ! empty($temp->options['instalment']) ? $temp->options['instalment'] : 2,
            ]);

            if ($trackDay) {
                service('Doc')->save($me, $trackDay, [
                    'type' => 'track-day',
                    'is_signed' => false,
                    'date' => now(),
                ]);

                if ( ! empty($temp->options['card'])) {
                    $params = [
                        'book_id' => 'track-day-'.$trackDay->id,
                        'amount' => $trackDay->price,
                        'order_id' => $trackDay->trackDayId().'-'.$trackDay->customers->count(),
                        'product_id' => $trackDay->trackDayId(),
                    ];

                    if ($temp->options['plan'] > 0) {
                        $params['instalment'] = $temp->options['instalment'];
                    }
                    
                    $card = service('Card')->get($temp->options['card']);
                    $result = service('Card')->charge($me, $card, $params);

                    if ($result) {
                        $trackDay->customers()->updateExistingPivot($me, [
                            'is_paid' => true,
                        ]);

                        service('PaymentHistory')->trackDay($trackDay, $me, $temp->options['plan'], $temp->options['instalment'], $trackDay->price);
                    }
                }
                
                $this->trackDayRepository->removeTemp($temp);
                event(new TrackDayBookedEvent($trackDay, $me));

                return true;
            }
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function bookCreate(array $input)
    {
        $user = service('User')->get($input['customer']);
        if ($user) {
            $trackDay = $this->get($input['trackDay']);

            if ($this->isAvailable($trackDay)) {
                $result = true;
                if ( ! empty($input['card'])) {
                    $params = [
                        'book_id' => 'track-day-'.$trackDay->id,
                        'amount' => $trackDay->price,
                        'order_id' => $trackDay->trackDayId().'-'.($trackDay->customers->count() + 1),
                        'product_id' => $trackDay->trackDayId(),
                    ];

                    if ($input['plan'] > 0) {
                        $params['instalment'] = $input['instalment'];
                    }
                    
                    $card = service('Card')->get($input['card']);
                    $result = service('Card')->charge($user, $card, $params);
                }

                if ($result) {
                    $trackDay = $this->trackDayRepository->connect($trackDay, $user, [
                        'card_id' => ! empty($input['card']) ? $input['card'] : 0,
                        'plan' => ! empty($input['plan']) ? $input['plan'] : 0,
                        'instalment' => ! empty($input['instalment']) ? $input['instalment'] : 2,
                    ]);

                    service('Doc')->save($user, $trackDay, [
                        'type' => 'track-day',
                        'is_signed' => false,
                        'date' => now(),
                    ]);

                    if ( ! empty($input['card'])) {
                        $trackDay->customers()->updateExistingPivot($user, [
                            'is_paid' => true,
                        ]);

                        service('PaymentHistory')->trackDay($trackDay, $user, $input['plan'], $input['instalment'], $trackDay->price);
                    }

                    event(new TrackDayBookCreatedEvent($trackDay, $user, $input['request']));
                }
                
                return $trackDay;
            }

            return response()->message('Sorry, all tickets were sold', 'error', 400);
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function isAvailable($trackDay)
    {
        return $trackDay->customers->count() < ($trackDay->riders1 + $trackDay->riders2 + $trackDay->riders3);
    }

    public function remove(TrackDay $trackDay)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            if ($trackDay->customers->count() == 0) {
                $this->trackDayRepository->detach($trackDay);
                $this->trackDayRepository->remove($trackDay);
                
                return true;
            }
            
            return response()->message('You can\'t remove this track day cause it was booked already', 'error', 400);
        }

        return response()->message('You haven\'t access to remove track days', 'error', 403);
    }

    public function bookings()
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $bookings = DB::table('track_day_user')->orderBy('created_at', 'desc')->get();

            $result = [];
            foreach ($bookings as $row) {
                $row->trackDay = $this->get($row->track_day_id);
                $row->user = service('User')->get($row->user_id);
                $row->card = service('Card')->get($row->card_id);

                $result[] = $row;
            }

            return $result;
        }

        return response()->message('You haven\'t access to track day bookings', 'error', 403);
    }

    public function pay(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $book = DB::table('track_day_user')->find($input['id']);
            if ( ! $book->is_paid) {
                if (empty($input['bank'])) {
                    $trackDay = $this->get($book->track_day_id);

                    $params = [
                        'book_id' => 'track-day-'.$trackDay->id,
                        'amount' => $trackDay->price,
                        'order_id' => $trackDay->trackDayId().'-'.($trackDay->customers->count() + 1),
                        'product_id' => $trackDay->trackDayId(),
                    ];

                    if ($book->plan > 0) {
                        $params['instalment'] = $book->instalment;
                    }
                    
                    $user = service('User')->get($book->user_id);
                    $card = service('Card')->get($book->card_id);
                    $result = service('Card')->charge($user, $card, $params);
                    if ($result) {
                        service('PaymentHistory')->trackDay($trackDay, $user, $book->plan, $book->instalment, $trackDay->price);
                    } else {
                        return response()->message('Something went wrong', 'error', 400);
                    }
                }

                DB::table('track_day_user')->where('id', $input['id'])->update([
                    'is_paid' => true,
                    'is_bank' => $input['bank'],
                ]);

                return true;
            }

            return response()->message('This booking was already paid', 'error', 422);    
        }

        return response()->message('You haven\'t access to pay bookings', 'error', 403);
    }

    public function enquiry(array $input)
    {
        $me = auth()->user();
        $isGuest = false;

        $temp = $this->trackDayRepository->getTemp($input['token']);
        if ($temp) {
            if (empty($me)) {
                if ( ! empty($temp->options['eForm'])) {
                    $me = service('User')->getByEmail($temp->options['eForm']['email']);
                    if (empty($me)) {
                        $me = service('Auth')->registerTemp($temp->options['eForm']);
                        $isGuest = true;
                    } else {
                        $me = service('User')->update($me, $temp->options['eForm']);
                    }
                }
            }

            if ( ! empty($me)) {
                $data = [
                    'track_day_id' => $temp->options['trackDay'],
                    'message' => $temp->options['message'],
                    'is_guest' => $isGuest,
                ];
                $enquiry = $this->trackDayRepository->createEnquiry($data);
                $enquiry = $this->trackDayRepository->connectEnquiry($enquiry, $me);
                
                if ($enquiry) {
                    $this->trackDayRepository->removeTemp($temp);

                    event(new TrackDayEnquiryCreatedEvent($enquiry));

                    return true;
                }
            }
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function enquiryAll()
    {
        $me = auth()->user();
        if ($me->role != 2) {
            return response()->message('You haven\'t access to enquiries', 'error', 403);
        }

        $enquiries = $this->trackDayRepository->enquiryAll();

        return $enquiries;
    }

    public function cardConnect($trackDay, $card_id, $book_id)
    {
        if ( ! empty($book_id)) {
            DB::table('track_day_user')->where('id', $book_id)->update([
                'card_id' => $card_id,
            ]);
        }
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->trackDayRepository->export($ids);
        foreach ($rows as $row) {
            $items[] = [
                'ID' => $row->id,
                'First name' => $row->firstname,
                'Last name' => $row->lastname,
                'Email' => $row->email,
                'Phone' => $row->phone,
                'Active' => $row->is_active ? 'Yes' : 'No',
            ];
        }

        return $items;
    }

    public function exportEnquiry($ids)
    {
        $items = [];
        $rows = $this->trackDayRepository->exportEnquiry($ids);
        foreach ($rows as $row) {
            $items[] = [
                'Date Enquiry' => $row->created_at->format('d/m/Y'),
                'First name' => $row->user->firstname,
                'Surname' => $row->user->lastname,
                'Email' => $row->user->email,
                'Phone' => $row->user->phone,
                'Event' => $row->event->name,
                'Price' => '£'.$row->event->price,
                'Message' => $row->message,
            ];
        }

        return $items;
    }

    public function exportBooking($ids)
    {
        $items = [];
        $rows = $this->trackDayRepository->exportBooking($ids);

        foreach ($rows as $row) {
            $row->event = $this->get($row->event_id);
            $row->user = service('User')->get($row->user_id);
            $row->card = service('Card')->get($row->card_id);

            $items[] = [
                'ID' => $row->id,
                'Event ID' => $row->event->eventId(),
                'Event' => $row->event->name,
                'Member Number' => $row->user->id,
                'First name' => $row->user->firstname,
                'Last name' => $row->user->lastname,
                'Email' => $row->user->email,
                'Phone' => $row->user->phone,
                'Region' => $row->event->region->title,
                'Coach' => ! empty($row->event->coach) ? ($row->event->coach->firstname.' '.$row->event->coach->lastname) : '',
                'Book Date' => (new Carbon($row->created_at))->format('d/m/Y'),
                'Price' => $row->event->price,
                'Fee' => $row->event->fee,
            ];
        }

        return $items;
    }
}
