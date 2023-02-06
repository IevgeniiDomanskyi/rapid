<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\AddressRepository;
use App\User;

class AddressService extends Service
{
    protected $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function get(User $user)
    {
        return $user->address;
    }

    public function save(User $user, array $input)
    {
        service('Card')->customerUpdate($user);
        
        if ($address = $user->address) {
            return $this->addressRepository->update($address, $input);
        }
        
        $address = $this->addressRepository->create($input);
        $user->address()->associate($address);
        $user->save();

        return $address;
    }
}
