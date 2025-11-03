<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;

class FacultyController extends Controller
{
    public function index()
    {
        return Faculty::with('courses.groups')->get();
    }

    public function show(Faculty $faculty)
    {
        return $faculty->load('courses.groups');
    }
}
