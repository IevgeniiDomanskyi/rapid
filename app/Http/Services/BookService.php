<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\BookRepository;
use App\Events\Book\TrackDateDefined as BookTrackDateDefinedEvent;
use App\Events\Book\TrackDateUpdate as BookTrackDateUpdateEvent;
use App\Events\Book\TrackPay as BookTrackPayEvent;
use App\Events\Book\AgreeRoadDates as BookAgreeRoadDatesEvent;
use App\Events\Book\Pay as BookPayEvent;
use App\Events\Book\UpdateRoadDates as BookUpdateRoadDatesEvent;
use App\Events\Book\PaymentRequest as BookPaymentRequestEvent;
use App\Events\Book\Created as BookCreatedEvent;
use App\Events\Book\AssignCoach as BookAssignCoachEvent;
use App\Events\Enquiry\Created as EnquiryCreatedEvent;
use App\Temp;
use App\Book;

class BookService extends Service
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function tempGet(string $token)
    {
        return $this->bookRepository->getTemp($token);
    }

    public function temp(array $input)
    {
        $temp = $this->bookRepository->getTemp($input['token']);
        if ( ! $temp) {
            $temp = new Temp();
        }

        $temp = $this->bookRepository->updateTemp($temp, $input);

        return $temp;
    }

    public function save(array $input)
    {
        $me = auth()->user();

        $temp = $this->bookRepository->getTemp($input['token']);
        if ($temp) {
            $voucher = false;
            if ( ! empty($temp->options['voucher'])) {
                $voucher = service('Voucher')->getByCode($temp->options['voucher']);
            }

            $status = 0;
            if ($temp->options['course'] != 1 || ($temp->options['course'] == 1 && $temp->options['level'] == 1)) {
                $status = 2;
            }

            $data = [
                'exp_id' => $temp->options['exp'],
                'course_id' => $temp->options['course'],
                'level_id' => $temp->options['level'],
                'card_id' => ! empty($temp->options['card']) ? $temp->options['card'] : 0,
                'postcode_id' => $temp->options['county'],
                'region_id' => $temp->options['region'],
                'voucher_id' => empty($voucher) ? 0 : $voucher->id,
                'plan' => ! empty($temp->options['plan']) ? $temp->options['plan'] : 0,
                'instalment' => ! empty($temp->options['instalment']) ? $temp->options['instalment'] : 2,
                'status' => $status,
            ];
            $book = $this->bookRepository->create($data);
            $book = $this->bookRepository->connect($book, $me);

            if (! empty($voucher)) {
                $voucher->users()->attach($me, ['book_id' => $book->id]);
            }

            if ($temp->options['terms']) {
                service('Doc')->save($me, $book, [
                    'type' => 'book',
                    'is_signed' => true,
                    'date' => now(),
                ]);
            }
            
            if ($book) {
                $this->bookRepository->removeTemp($temp);
                event(new BookCreatedEvent($book, false));

                service('User')->customersPostcodeSave($me, $temp->options['county']);
                service('User')->customersRegionSave($me, $temp->options['region']);

                return true;
            }
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function enquiry(array $input)
    {
        $me = auth()->user();
        $isGuest = false;

        $temp = $this->bookRepository->getTemp($input['token']);
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
                    'exp_id' => $temp->options['exp'],
                    'course_id' => $temp->options['course'],
                    'level_id' => $temp->options['level'],
                    'message' => $temp->options['message'],
                    'is_guest' => $isGuest,
                ];
                $enquiry = $this->bookRepository->createEnquiry($data);
                $enquiry = $this->bookRepository->connectEnquiry($enquiry, $me);
                
                if ($enquiry) {
                    $this->bookRepository->removeTemp($temp);

                    event(new EnquiryCreatedEvent($enquiry));

                    return true;
                }
            }
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function all()
    {
        $me = auth()->user();
        $books = $this->bookRepository->all();

        return $books;
    }

    public function enquiryAll()
    {
        $me = auth()->user();
        if ($me->role != 2) {
            return response()->message('You haven\'t access to enquiries', 'error', 403);
        }

        $enquiries = $this->bookRepository->enquiryAll();

        return $enquiries;
    }

    public function get($id)
    {
        return $this->bookRepository->get($id);
    }

    public function update(Book $book, array $input)
    {
        return $this->bookRepository->update($book, $input);
    }

    public function road(Book $book, $road)
    {
        return $this->update($book, [
            'road' => $road,
            'status' => 6,
        ]);
    }

    public function trackDateDefine(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $book = $this->bookRepository->get($input['book']);
            $date = service('Track')->getDate($input['date']);
            if (empty($book->trackDate) || $book->trackDate->count() == 0) {
                $this->bookRepository->defineTrackDate($book, $date);
                $this->bookRepository->update($book, [
                    'status' => 1,
                ]);

                event(new BookTrackDateDefinedEvent($book));
            } else {
                if ($book->trackDate->id != $date->id) {
                    $this->bookRepository->defineTrackDate($book, $date);

                    event(new BookTrackDateUpdateEvent($book, $book->user, $book->trackDate));
                }
            }

            return true;
        }

        return response()->message('You haven\'t access to define track date', 'error', 403);
    }

    public function trackPay(array $input)
    {
        $me = auth()->user();
        if ($me->role == 1 || $me->role == 2) {
            $book = $this->bookRepository->get($input['book']);
            $this->bookRepository->update($book, [
                'status' => $input['pay'] ? 2 : 1,
            ]);

            // event(new BookTrackPayEvent($book->user, $book));

            return true;
        }

        return response()->message('You haven\'t access to pay track', 'error', 403);
    }

    public function assign(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            
            $book = $this->bookRepository->get($input['book']);
            $coach = service('Coach')->get($input['coach']);

            $update = false;
            if ($book->coach) {
                $update = true;
            }

            $this->bookRepository->assign($book, $coach);
            $this->bookRepository->update($book, [
                'status' => $update ? $book->status : 3,
                'fee' => $input['fee'],
            ]);

            if ( ! $update) {
                service('Dialog')->assignCoach($book->dialog, $coach);
            }

            event(new BookAssignCoachEvent($book, $coach, $book->user));

            return true;
        }

        return response()->message('You haven\'t access to assign coach', 'error', 403);
    }

    public function roadDateDefine(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $book = $this->bookRepository->get($input['book']);
            if (empty($input['date'])) {
                for ($i = $input['road']; $i <= 6; $i++) {
                    $this->bookRepository->roadDateRemove($book, $i);
                }
            } else {
                $isUpdate = false;
                $newDate = service('Date')->get($input['date']);
                $date = $this->bookRepository->roadDate($book, $input['road']);
                if ( ! empty($date) && ($date->id != $newDate->id || ($date->id == $newDate->id && $date->pivot->slot != $input['slot']))) {
                    $this->bookRepository->roadDateRemove($book, $input['road']);
                    $isUpdate = true;
                }
                
                $this->bookRepository->attachDate($book, $newDate, [
                    'road' => $input['road'],
                    'slot' => $input['slot'],
                ]);

                if ($isUpdate) {
                    service('Doc')->generate($book, true);
                    event(new BookUpdateRoadDatesEvent($book));
                }
            }
            return true;
        }

        return response()->message('You haven\'t access to define track date', 'error', 403);
    }

    public function agreeRoadDates(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            
            $book = $this->bookRepository->get($input['book']);
            if ($book->status < 5) {
                $this->bookRepository->update($book, [
                    'status' => 5,
                ]);

                service('Doc')->generate($book);

                event(new BookAgreeRoadDatesEvent($book));

                return true;
            }

            return response()->message('Something went wrong', 'error', 400);
        }

        return response()->message('You haven\'t access to agree road dates', 'error', 403);
    }

    public function pay(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            
            $book = $this->bookRepository->get($input['id']);
            if ( ! $book->is_paid) {
                if (empty($input['bank'])) {
                    $amount = $book->level->price;

                    if ( ! empty($book->voucher)) {
                        $check = true;
                        foreach ($book->voucher->levels as $level) {
                            if ($level->id == $book->level->id) {
                                $check = false;
                            }
                        }

                        if ($check) {
                            if ($book->voucher->type == 1) {
                                $amount = round($amount * $book->voucher->amount / 100);
                            } else {
                                $amount = round($amount - $book->voucher->amount);
                            }
                        }
                    }  

                    $params = [
                        'book_id' => $book->id,
                        'amount' => $amount,
                        'order_id' => $book->orderId(),
                        'product_id' => $book->level->crm_id,
                    ];

                    if ($book->plan > 0) {
                        $params['instalment'] = $book->instalment;
                    }

                    $result = service('Card')->charge($book->user, $book->card, $params);
                    if ($result) {
                        service('PaymentHistory')->add($book, $amount);

                        event(new BookPayEvent($book, $amount));
                    } else {
                        return response()->message('Something went wrong', 'error', 400);
                    }
                }

                $this->bookRepository->update($book, [
                    'is_paid' => true,
                    'is_bank' => $input['bank'],
                ]);

                return true;
            }

            return response()->message('This booking was already paid', 'error', 422);    
        }

        return response()->message('You haven\'t access to pay bookings', 'error', 403);
    }

    public function requestPayment(array $input)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $book = $this->bookRepository->get($input['book']);
            $this->bookRepository->update($book, [
                'status' => 5,
            ]);

            event(new BookPaymentRequestEvent($book));

            return true;
        }

        return response()->message('You haven\'t access to send Payment request', 'error', 403);
    }

    public function forPay()
    {
        $me = auth()->user();
        return $this->bookRepository->getForPay($me);
    }

    public function create(array $input)
    {
        $user = service('User')->get($input['customer']);
        if ($user) {
            $voucher = false;
            if ( ! empty($input['voucher'])) {
                if ( ! ($voucher = service('Voucher')->check($input['voucher'], $user))) {
                    return false;
                }
            }

            $status = 0;
            if ($input['course'] != 1 || ($input['course'] == 1 && $input['level'] == 1)) {
                $status = 2;
            }

            $data = [
                'exp_id' => ! empty($user->exp) ? $user->exp->id : 0,
                'course_id' => $input['course'],
                'level_id' => $input['level'],
                'card_id' => ! empty($input['card']) ? $input['card'] : 0,
                'region_id' => $input['region'],
                'voucher_id' => ! empty($voucher) ? $voucher->id : 0,
                'plan' => $input['plan'],
                'instalment' => $input['instalment'],
                'status' => $status,
            ];
            $book = $this->bookRepository->create($data);
            $book = $this->bookRepository->connect($book, $user);

            if (! empty($voucher)) {
                $voucher->users()->attach($user, ['book_id' => $book->id]);
            }

            service('Doc')->save($user, $book, [
                'type' => 'book',
                'is_signed' => false,
                'date' => now(),
            ]);

            event(new BookCreatedEvent($book, $input['request']));
            
            return $book;
        }

        return response()->message('Something went wrong', 'error', 422);
    }

    public function twoDaysUsers()
    {
        $result = [];
        $books = $this->bookRepository->all();
        foreach ($books as $book) {
            if ($book->status < 7) {
                if ($book->trackDate) {
                    if (now()->startOfDay()->diffInDays($book->trackDate->date, false) == 2) {
                        $result[] = [
                            'book' => $book,
                            'user' => $book->user,
                            'type' => 'track',
                            'date' => $book->trackDate->date,
                        ];
                    }
                }

                if ($book->dates && $book->dates->count() > 0) {
                    foreach ($book->dates as $date) {
                        if (now()->startOfDay()->diffInDays($date->date->startOfDay(), false) == 2) {
                            $result[] = [
                                'book' => $book,
                                'user' => $book->user,
                                'type' => 'road',
                                'date' => $date->date,
                            ];
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function cardConnect(Book $book, $card_id, $item_id)
    {
        $this->update($book, [
            'card_id' => $card_id,
        ]);
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->bookRepository->export($ids);
        foreach ($rows as $row) {
            $doc = $row->docs()->where('type', 'book')->first();
            $opt_in = '';
            if ( ! empty($doc)) {
                $opt_in = $doc->created_at->format('d/m/Y');
            }

            $doc = $row->docs()->where('type', 'certificate')->first();
            $completion_date = '';
            if ( ! empty($doc)) {
                $completion_date = $doc->created_at->format('d/m/Y');
            }

            $items[] = [
                'Course Ref No' => $row->orderId(),
                'Date Booked' => $row->created_at->format('d/m/Y'),
                'User ID' => $row->user->id,
                'First name' => $row->user->firstname,
                'Surname' => $row->user->lastname,
                'DOB' => ! empty($row->user->dob) ? $row->user->dob->format('d/m/Y') : '',
                'Exp ID' => ! empty($row->user->exp) ? $row->user->exp->crm_id : '',
                'Registration Date' => $row->user->created_at->format('d/m/Y'),
                'Last Access Date' => ! empty($row->user->activity_at) ? $row->user->activity_at->format('d/m/Y') : '',
                'Opt In / Out' => $opt_in,
                'Email' => $row->user->email,
                'Phone' => $row->user->phone,
                'Region' => ! empty($row->region) ? $row->region->title : '',
                'Coach ID' =>  ! empty($row->coach) ? $row->coach->id : '',
                'Coach Surname' =>  ! empty($row->coach) ? $row->coach->lastname : '',
                'Fee' => $row->fee,
                'Booked Date' => $row->created_at->format('d/m/Y'),
                'Course ID' => $row->course->crm_id,
                'BM/RM/B' => $row->course->short(),
                'Level ID' => $row->level->crm_id,
                'Level' => $row->level->crm_id == 'Level_b_fd' ? 'FD' : $row->level->level,
                'Number of Road Days' => $row->level->road_dates,
                'Track Date ID' => ! empty($row->trackDate) ? $row->trackDate->id : '',
                'Track Date' => ! empty($row->trackDate) ? $row->trackDate->date->format('d/m/Y') : '',
                'Days Booked' => ! empty($row->dates) ? $row->dates->count() : '',
                'Day 1' => ! empty($row->dates[0]) ? $row->dates[0]->date->format('d/m/Y') : '',
                'Day 2' => ! empty($row->dates[1]) ? $row->dates[1]->date->format('d/m/Y') : '',
                'Day 3' => ! empty($row->dates[2]) ? $row->dates[2]->date->format('d/m/Y') : '',
                'Day 4' => ! empty($row->dates[3]) ? $row->dates[3]->date->format('d/m/Y') : '',
                'Day 5' => ! empty($row->dates[4]) ? $row->dates[4]->date->format('d/m/Y') : '',
                'Day 6' => ! empty($row->dates[5]) ? $row->dates[5]->date->format('d/m/Y') : '',
                'Completion date' => $completion_date,
            ];
        }

        return $items;
    }

    public function exportEnquiry($ids)
    {
        $items = [];
        $rows = $this->bookRepository->exportEnquiry($ids);
        foreach ($rows as $row) {
            $items[] = [
                'Date Enquiry' => $row->created_at->format('d/m/Y'),
                'First name' => $row->user->firstname,
                'Surname' => $row->user->lastname,
                'Email' => $row->user->email,
                'Phone' => $row->user->phone,
                'Post Code' => ! empty($row->user->postcodes[0]) ? $row->user->postcodes[0]->code.' ('.$row->user->postcodes[0]->area.')' : '',
                'Course' => $row->course->short(),
                'Level' => $row->level->crm_id == 'Level_b_fd' ? 'FD' : $row->level->level,
                'Price' => '£'.$row->level->price,
                'Message' => $row->message,
            ];
        }

        return $items;
    }
}
