<?php

namespace App\Http\Services;

use App\Http\Services\Service;
use App\Http\Repositories\CourseRepository;
use App\Course;

class CourseService extends Service
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function all()
    {
        return $this->courseRepository->all();
    }
}
