<?php

namespace App\Http\Repositories;

use App\Course;

class CourseRepository
{
    public function get($id)
    {
        return Course::find($id);
    }
    
    public function all()
    {
        return Course::with('levels')->get();
    }
}
