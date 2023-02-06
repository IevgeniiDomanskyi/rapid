<?php

namespace App\Http\Repositories;

use App\Address;

class AddressRepository
{
    public function create(array $data)
    {
        return Address::create($data);
    }

    public function update(Address $address, array $data)
    {
        $address->fill($data);
        $address->save();
        return $address;
    }
}
