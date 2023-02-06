<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\Service;
use App\Http\Repositories\HashRepository;
use App\Hash;

class HashService extends Service
{
    protected $hashRepository;

    public function __construct(HashRepository $hashRepository)
    {
        $this->hashRepository = $hashRepository;
    }

    public function get($object, string $type)
    {
        return $this->hashRepository->get($object, $type);
    }

    public function getByHash(Hash $hash, string $type, $instance)
    {
        return $this->hashRepository->getByHash($hash->hash, $type, $instance);
    }

    public function create($object, string $type, array $data = [])
    {
        return $this->hashRepository->create($object, $type, $data);
    }

    public function remove($object, string $type)
    {
        return $this->hashRepository->remove($object, $type);
    }
}
