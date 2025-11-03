<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $groupId = $request->query('group_id');
        $query = Schedule::query();

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->get();
    }


    public function show($id)
    {
        return Schedule::with('group.course.faculty')->findOrFail($id);
    }
}
