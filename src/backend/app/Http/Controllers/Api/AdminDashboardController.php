<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use App\Models\Schedule;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users_count' => User::count(),
            'groups_count' => Group::count(),
            'schedules_count' => Schedule::count(),
        ]);
    }
}
