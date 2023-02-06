<?php

namespace App\Http\Repositories;

use App\User;

class CoachRepository
{
    public function get($id)
    {
        return User::find($id);
    }
    
    public function all()
    {
        return User::where('role', 1)->get();
    }

    public function filter(array $filter)
    {
        $query = User::where('role', 1);
        foreach ($filter as $field => $value) {
            $query->where($field, $value);
        }
        return $query->get();
    }

    public function byPostcode(int $postcode_id)
    {
        return User::where('role', 1)->wherehas('postcodes', function($q) use ($postcode_id) {
            $q->where('postcodes.id', $postcode_id);
        })->get();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(User $coach, array $data)
    {
        $coach->fill($data);
        $coach->save();
        return $coach;
    }

    public function regions(User $coach, array $data)
    {
        $coach->regions()->sync($data);
        return $coach;
    }

    public function export($ids)
    {
        if (empty($ids)) {
            return User::where('role', 1)->get();
        } else {
            return User::where('role', 1)->whereIn('id', $ids)->get();
        }
    }
}
