<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * 📋 Отримати всі розклади або за конкретною групою
     */
    public function index(Request $request)
    {
        $query = Schedule::with('group.course.faculty');

        // 🔎 Фільтрація за group_id (для сторінки групи)
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // 🔎 Додатково — фільтр за тижнем (опціонально)
        if ($request->filled('week')) {
            $query->where('week', $request->week);
        }

        return response()->json($query->orderBy('day')->orderBy('time')->get());
    }

    /**
     * 👁️ Один запис розкладу
     */
    public function show($id)
    {
        $schedule = Schedule::with('group.course.faculty')->findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * ➕ Створити новий розклад
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required|string|max:50',
            'pair_number' => 'required|integer|min:1|max:8', // ✅ нове
            'time' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'room' => 'nullable|string|max:100',
            'teacher' => 'nullable|string|max:255',
            'week' => 'nullable|string|max:50',
        ]);

        $schedule = Schedule::create($validated);
        return response()->json($schedule->load('group'), 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'group_id' => 'sometimes|exists:groups,id',
            'day' => 'sometimes|string|max:50',
            'pair_number' => 'sometimes|integer|min:1|max:8', // ✅ нове
            'time' => 'sometimes|string|max:50',
            'subject' => 'sometimes|string|max:255',
            'room' => 'nullable|string|max:100',
            'teacher' => 'nullable|string|max:255',
            'week' => 'nullable|string|max:50',
        ]);

        $schedule->update($validated);
        return response()->json($schedule->load('group'));
    }


    /**
     * ❌ Видалити запис розкладу
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => '✅ Розклад видалено успішно']);
    }
}
