<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\PostcodeRepository;
use App\Postcode;

class PostcodeService extends Service
{
    protected $postcodeRepository;

    public function __construct(PostcodeRepository $postcodeRepository)
    {
        $this->postcodeRepository = $postcodeRepository;
    }

    public function all()
    {
        return $this->postcodeRepository->all();
    }

    public function available()
    {
        $coaches = service('Coach')->all();
        $postcodes = [];
        foreach ($coaches as $coach) {
            foreach ($coach->postcodes as $postcode) {
                $postcodes[$postcode->code] = $postcode;
            }
        }
        ksort($postcodes);
        return array_values($postcodes);
    }
}
