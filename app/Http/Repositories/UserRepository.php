<?php

namespace App\Http\Repositories;

use App\User;

class UserRepository
{
    public function customers()
    {
        return User::where('role', 0)->get();
    }

    public function get($id)
    {
        return User::find($id);
    }
    
    public function getByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getByAuthToken($token)
    {
        return User::where('auth_token', $token)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function postcode(User $user, $postcode_id)
    {
        $user->postcodes()->sync([$postcode_id]);
        return $user;
    }

    public function region(User $user, $region_id)
    {
        $user->regions()->sync([$region_id]);
        return $user;
    }

    public function admins()
    {
        return User::where('role', 2)->get();
    }

    public function export($ids)
    {
        return empty($ids) ? User::where('role', 0)->get() : User::where('role', 0)->whereIn('id', $ids)->get();
    }
}
