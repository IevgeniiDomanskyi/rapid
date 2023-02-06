<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\ExpRepository;
use App\Exp;

class ExpService extends Service
{
    protected $expRepository;

    public function __construct(ExpRepository $expRepository)
    {
        $this->expRepository = $expRepository;
    }

    public function all()
    {
        return $this->expRepository->all();
    }

    public function getByValue(int $value)
    {
        return $this->expRepository->getByValue($value);
    }
}
