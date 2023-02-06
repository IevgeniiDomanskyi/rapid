<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\UserRepository;
use App\Events\User\CustomerSave as UserCustomerSaveEvent;
use App\Notifications\User\RequestPaymentDetails as UserRequestPaymentDetailsNotification;
use App\User;

class UserService extends Service
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get($id)
    {
        return $this->userRepository->get($id);
    }

    public function customers()
    {
        return $this->userRepository->customers();
    }

    public function create(array $input)
    {
        return $this->userRepository->create($input);
    }

    public function update(User $user, $data)
    {
        return $this->userRepository->update($user, $data);
    }
    
    public function getByEmail($email)
    {
        return $this->userRepository->getByEmail($email);
    }

    public function getByAuthToken($token)
    {
        return $this->userRepository->getByAuthToken($token);
    }

    public function customersSave(array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $password = substr(md5($input['email']), 0, 8);

            $data = [
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'postcode' => $input['postcode'],
                'password' => bcrypt($password),
                'role' => 0,
                'is_active' => false,
                'is_notified' => true,
            ];

            $customer = $this->userRepository->create($data);
            //$customer = $this->userRepository->postcode($customer, $input['postcode']);

            $address = [
                'line1' => $input['line1'],
                'line2' => $input['line2'],
                'town' => $input['town'],
                'county' => ! empty($input['county']) ? $input['county'] : '',
                'country' => $input['country'],
                'postcode' => $input['postcode'],
            ];

            service('Address')->save($customer, $address);

            event(new UserCustomerSaveEvent($customer, $password));

            return $customer;
        }

        return response()->message('You haven\'t access to create customer', 'error', 403);
    }

    public function customersUpdate(User $customer, array $input)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $data = [
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'phone' => $input['phone'],
                'postcode' => $input['postcode'],
            ];

            $customer = $this->userRepository->update($customer, $data);

            $address = [
                'line1' => $input['line1'],
                'line2' => $input['line2'],
                'town' => $input['town'],
                'county' => ! empty($input['county']) ? $input['county'] : '',
                'country' => $input['country'],
                'postcode' => $input['postcode'],
            ];

            service('Address')->save($customer, $address);

            return $customer;
        }

        return response()->message('You haven\'t access to update customer', 'error', 403);
    }

    public function customersPostcodeSave(User $user, $postcode_id)
    {
        $user = $this->userRepository->postcode($user, $postcode_id);
        return $user;
    }

    public function customersRegionSave(User $user, $region_id)
    {
        $user = $this->userRepository->region($user, $region_id);
        return $user;
    }

    public function admins()
    {
        return $this->userRepository->admins();
    }

    public function activity()
    {
        $me = auth()->user();
        $this->update($me, [
            'activity_at' => now(),
        ]);
    }

    public function import()
    {
        $file_handle = fopen(resource_path().'/csv/customer_database.csv', 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, ',');
        }
        fclose($file_handle);
        
        foreach ($line_of_text as $key => $row) {
            if ($key > 0) {
                $email = trim(strtolower($row[11]));
                if (strpos($email, '@') !== false) {
                    $user = $this->getByEmail($email);
                    if (empty($user->id)) {
                        $password = substr(md5($email), 0, 8);

                        $data = [
                            'firstname' => $row[4],
                            'lastname' => $row[5],
                            'email' => $email,
                            'phone' => str_replace([' ', '.'], '', trim($row[12])),
                            'postcode' => $row[13],
                            'password' => bcrypt($password),
                            'role' => 0,
                            'is_active' => false,
                            'is_notified' => false,
                        ];

                        $user = $this->userRepository->create($data);
                    }
                }
            }
        }
        return true;
    }

    public function requestPaymentDetails(User $user)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $user = $this->userRepository->update($user, [
                'payment_request' => true,
            ]);

            $user->notify(new UserRequestPaymentDetailsNotification($user));

            return true;
        }

        return response()->message('You haven\'t access to send requests to customer', 'error', 403);
    }

    public function cardConnect(array $data)
    {
        $me = auth()->user();

        if ($me->role == 2) {
            $item = service(ucfirst($data['type']))->get($data['id']);
            $item = service(ucfirst($data['type']))->cardConnect($item, $data['card'], $data['id']);

            return true;
        }

        return response()->message('You haven\'t access to add cards', 'error', 403);
    }

    public function export($ids)
    {
        $items = [];
        $rows = $this->userRepository->export($ids);
        foreach ($rows as $row) {
            $items[] = [
                'First name' => $row->firstname,
                'Last name' => $row->lastname,
                'Email' => $row->email,
                'Phone' => $row->phone,
                'DOB' => ! empty($row->dob) ? $row->dob->format('d/m/Y') : '',
                'Active' => $row->is_active ? 'Yes' : 'No',
            ];
        }

        return $items;
    }
}