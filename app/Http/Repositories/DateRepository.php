<?php

namespace App\Http\Repositories;

use App\Date;
use App\User;

class DateRepository
{
    public function get($id)
    {
        return Date::find($id);
    }

    public function all(User $coach)
    {
        return $coach->dates()->with('users')->get();
    }

    public function create(array $data)
    {
        return Date::create($data);
    }

    public function connect(User $coach, Date $date)
    {
        $date->coach()->associate($coach);
        $date->save();
        return $date;
    }

    public function remove(Date $date)
    {
        return $date->delete();
    }
}
