<?php

namespace App\Http\Repositories;

use Illuminate\Support\Str;

class HashRepository
{
    public function get($object, string $type)
    {
        return $object->hashes()->where('type', $type)->first();
    }

    public function getByHash(string $hash, string $type, $instance)
    {
        return $instance::whereHas('hashes', function($q) use ($hash, $type) {
            $q->where('hash', $hash);
            $q->where('type', $type);
        })->first();
    }

    public function create($object, string $type, array $data = [], $expired_at = null)
    {
        $hash = $object->hashes()->firstOrNew([
            'type' => $type,
        ]);

        if ($hash->exists) {
            $object->hashes()->detach($hash->id);
        }

        foreach ($data as $key => $value) {
            $hash->{$key} = $value;
        }

        $hash->hash = md5(Str::random(10));
        $hash->expired_at = $expired_at;
        $object->hashes()->save($hash);

        return $hash->hash;
    }

    public function remove($object, string $type)
    {
        $object->hashes()->where('type', $type)->get()->each(function($value){
            $value->delete();
        });
    }
}
