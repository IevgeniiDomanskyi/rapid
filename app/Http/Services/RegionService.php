<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\RegionRepository;
use App\Region;

class RegionService extends Service
{
    protected $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function all()
    {
        return $this->regionRepository->all();
    }
}
