<?php

namespace App\Http\Services;

use App\Events\Coach\Create as CoachCreateEvent;
use App\Events\Coach\Password as CoachPasswordEvent;
use App\Http\Services\Service;
use App\Http\Repositories\CoachRepository;
use App\User;

class CoachService extends Service
{
    protected $coachRepository;

    public function __construct(CoachRepository $coachRepository)
    {
        $this->coachRepository = $coachRepository;
    }

    public function all()
    {
        return $this->coachRepository->all();
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
        $me = auth()->user();

        if ($me->role == 2) {
            $password = substr(md5($input['email']), 0, 8);

            $data = [
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'password' => bcrypt($password),
                'role' => 1,
                'gdpr' => true,
            ];

            $coach = $this->coachRepository->create($data);
            $coach = $this->coachRepository->regions($coach, $input['regions']);

            event(new CoachCreateEvent($coach, $password));

            return $coach;
        }

        return response()->message('You haven\'t access to create coach', 'error', 403);
    }

    public function password(User $coach)
    {
        $me = auth()->user();

        if ($me->role == 1 || $me->role == 2) {
            $password = substr(md5($coach->password), 0, 8);

            $data = [
                'password' => bcrypt($password),
            ];

            $coach = $this->coachRepository->update($coach, $data);

            event(new CoachPasswordEvent($coach, $password));

            return true;
        }

        return response()->message('You haven\'t access to reset password', 'error', 403);
    }

    public function customers()
    {
        $me = auth()->user();

        if ($me->role == 1) {
            $customers = [];
            foreach ($me->coachBooks as $book) {
                $customers[$book->user->id] = $book->user;
            }
            
            return $customers;
        }

        return [];
    }

    public function books()
    {
        $me = auth()->user();

        if ($me->role == 1) {
            return $me->coachBooks;
        }

        return [];
    }

    public function enquiries()
    {
        $me = auth()->user();

        if ($me->role == 1) {
            $enquiries = [];
            foreach ($me->coachBooks as $book) {
                foreach ($book->user->enquiries as $enquiry) {
                    $enquiries[$enquiry->id] = $enquiry;
                }
            }
            
            return array_values($enquiries);
        }

        return [];
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->coachRepository->export($ids);
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
}
