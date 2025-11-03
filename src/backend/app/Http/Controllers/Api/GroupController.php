<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        // Віддаємо всі групи з курсами і факультетами
        return Group::with('course.faculty')->get();
    }

    public function show($id)
    {
        // Знайти групу з її курсом і факультетом
        return Group::with('course.faculty')->findOrFail($id);
    }
}
