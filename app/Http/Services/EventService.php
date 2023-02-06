<?php

namespace App\Http\Services;

use DB;
use Carbon\Carbon;
use App\Events\Event\AssignCoach as EventAssignCoachEvent;
use App\Events\Event\Booked as EventBookedEvent;
use App\Events\Event\BookCreated as EventBookCreatedEvent;
use App\Events\Event\EnquiryCreated as EventEnquiryCreatedEvent;
use App\Http\Services\Service;
use App\Http\Repositories\EventRepository;
use App\Event;
use App\Temp;

class EventService extends Service
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function all()
    {
        return $this->eventRepository->all();
    }

    public function get(int $id)
    {
        return $this->eventRepository->get($id);
    }

    public function tempGet(string $token)
    {
        return $this->eventRepository->getTemp($token);
    }

    public function temp(array $input)
    {
        $temp = $this->eventRepository->getTemp($input['token']);
        if ( ! $temp) {
            $temp = new Temp();
        }

        $temp = $this->eventRepository->updateTemp($temp, $input);

        return $temp;
    }

    public function save(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            foreach ($input['regions'] as $region_id) {
                $data = [
                    'region_id' => $region_id,
                    'coach_id' => empty($input['coach']) ? 0 : $input['coach'],
                    'name' => $input['name'],
                    'type' => $input['type'],
                    'from' => $input['from'],
                    'to' => $input['to'],
                    'riders' => $input['riders'],
                    'price' => $input['price'],
                    'fee' => $input['fee'],
                ];

                $event = $this->eventRepository->create($data);
            }

            if ( ! empty($input['coach'])) {
                $event->load('coach');
                event(new EventAssignCoachEvent($event));
            }

            return true;
        }

        return response()->message('You haven\'t access to create event', 'error', 403);
    }

    public function update(Event $event, array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $old_coach_id = ! empty($event->coach->id) ? $event->coach->id : 0;

            $data = [
                'coach_id' => empty($input['coach']) ? 0 : $input['coach'],
                'name' => $input['name'],
                'type' => $input['type'],
                'from' => $input['from'],
                'to' => $input['to'],
                'riders' => $input['riders'],
                'price' => $input['price'],
                'fee' => $input['fee'],
            ];

            $event = $this->eventRepository->update($event, $data);

            if ( ! empty($input['coach']) && $old_coach_id != $input['coach']) {
                $event->load('coach');
                event(new EventAssignCoachEvent($event));
            }

            return true;
        }

        return response()->message('You haven\'t access to update events', 'error', 403);
    }

    public function customers(Event $event)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            return $event->customers;
        }

        return [];
    }

    public function book(Event $event, array $input)
    {
        $me = auth()->user();

        $temp = $this->eventRepository->getTemp($input['token']);
        if ($temp) {
            $event = $this->eventRepository->connect($event, $me, [
                'card_id' => ! empty($temp->options['card']) ? $temp->options['card'] : 0,
                'plan' => ! empty($temp->options['plan']) ? $temp->options['plan'] : 0,
                'instalment' => ! empty($temp->options['instalment']) ? $temp->options['instalment'] : 2,
            ]);

            if ($event) {
                service('Doc')->save($me, $event, [
                    'type' => 'event',
                    'is_signed' => false,
                    'date' => now(),
                ]);

                if ( ! empty($temp->options['card'])) {
                    $params = [
                        'book_id' => 'event-'.$event->id,
                        'amount' => $event->price,
                        'order_id' => $event->eventId().'-'.$event->customers->count(),
                        'product_id' => $event->eventId(),
                    ];

                    if ($temp->options['plan'] > 0) {
                        $params['instalment'] = $temp->options['instalment'];
                    }
                    
                    $card = service('Card')->get($temp->options['card']);
                    $result = service('Card')->charge($me, $card, $params);
                    if ($result) {
                        $event->customers()->updateExistingPivot($me, [
                            'is_paid' => true,
                        ]);

                        service('PaymentHistory')->event($event, $me, $temp->options['plan'], $temp->options['instalment'], $event->price);
                    }
                }
                
                $this->eventRepository->removeTemp($temp);
                event(new EventBookedEvent($event, $me));

                return true;
            }
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function bookCreate(array $input)
    {
        $user = service('User')->get($input['customer']);
        if ($user) {
            $event = $this->get($input['event']);

            if ($event->customers->count() < $event->riders) {
                $result = true;

                if ( ! empty($input['card'])) {
                    $params = [
                        'book_id' => 'event-'.$event->id,
                        'amount' => $event->price,
                        'order_id' => $event->eventId().'-'.($event->customers->count() + 1),
                        'product_id' => $event->eventId(),
                    ];

                    if ($input['plan'] > 0) {
                        $params['instalment'] = $input['instalment'];
                    }
                    
                    $card = service('Card')->get($input['card']);
                    $result = service('Card')->charge($user, $card, $params);
                }

                if ($result) {
                    $event = $this->eventRepository->connect($event, $user, [
                        'card_id' => ! empty($input['card']) ? $input['card'] : 0,
                        'plan' => ! empty($input['plan']) ? $input['plan'] : 0,
                        'instalment' => ! empty($input['instalment']) ? $input['instalment'] : 2,
                    ]);

                    service('Doc')->save($user, $event, [
                        'type' => 'event',
                        'is_signed' => false,
                        'date' => now(),
                    ]);

                    if ( ! empty($input['card'])) {
                        $event->customers()->updateExistingPivot($user, [
                            'is_paid' => true,
                        ]);

                        service('PaymentHistory')->event($event, $user, $input['plan'], $input['instalment'], $event->price);
                    }

                    event(new EventBookCreatedEvent($event, $user, $input['request']));
                }
                
                return $event;
            }

            return response()->message('Sorry, all tickets were sold', 'error', 400);
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function remove(Event $event)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            if ($event->customers->count() == 0) {
                $this->eventRepository->detach($event);
                $this->eventRepository->remove($event);
                
                return true;
            }
            
            return response()->message('You can\'t remove this event cause it was booked already', 'error', 400);
        }

        return response()->message('You haven\'t access to update events', 'error', 403);
    }

    public function bookings()
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $bookings = DB::table('event_user')->orderBy('created_at', 'desc')->get();

            $result = [];
            foreach ($bookings as $row) {
                $row->event = $this->get($row->event_id);
                $row->user = service('User')->get($row->user_id);
                $row->card = service('Card')->get($row->card_id);

                $result[] = $row;
            }

            return $result;
        }

        return response()->message('You haven\'t access to event bookings', 'error', 403);
    }

    public function pay(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $book = DB::table('event_user')->find($input['id']);
            if ( ! $book->is_paid) {
                if (empty($input['bank'])) {
                    $event = $this->get($book->event_id);

                    $params = [
                        'book_id' => 'event-'.$event->id,
                        'amount' => $event->price,
                        'order_id' => $event->eventId().'-'.($event->customers->count() + 1),
                        'product_id' => $event->eventId(),
                    ];

                    if ($book->plan > 0) {
                        $params['instalment'] = $book->instalment;
                    }
                    
                    $user = service('User')->get($book->user_id);
                    $card = service('Card')->get($book->card_id);
                    $result = service('Card')->charge($user, $card, $params);
                    if ($result) {
                        service('PaymentHistory')->event($event, $user, $book->plan, $book->instalment, $event->price);
                    } else {
                        return response()->message('Something went wrong', 'error', 400);
                    }
                }

                DB::table('event_user')->where('id', $input['id'])->update([
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

        $temp = $this->eventRepository->getTemp($input['token']);
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
                    'event_id' => $temp->options['event'],
                    'message' => $temp->options['message'],
                    'is_guest' => $isGuest,
                ];
                $enquiry = $this->eventRepository->createEnquiry($data);
                $enquiry = $this->eventRepository->connectEnquiry($enquiry, $me);
                
                if ($enquiry) {
                    $this->eventRepository->removeTemp($temp);

                    event(new EventEnquiryCreatedEvent($enquiry));

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

        $enquiries = $this->eventRepository->enquiryAll();

        return $enquiries;
    }

    public function cardConnect($event, $card_id, $book_id)
    {
        if ( ! empty($book_id)) {
            DB::table('event_user')->where('id', $book_id)->update([
                'card_id' => $card_id,
            ]);
        }
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->eventRepository->export($ids);
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
        $rows = $this->eventRepository->exportEnquiry($ids);
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
        $rows = $this->eventRepository->exportBooking($ids);

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
