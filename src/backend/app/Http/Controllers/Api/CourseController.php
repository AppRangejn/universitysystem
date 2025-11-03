<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // 📋 Усі курси
    public function index()
    {
        return response()->json(Course::with('faculty')->get());
    }

    // 👁️ Один курс
    public function show($id)
    {
        $course = Course::with('faculty')->findOrFail($id);
        return response()->json($course);
    }

    // ➕ Створити курс
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $course = Course::create($validated);
        return response()->json($course, 201);
    }

    // ✏️ Оновити курс
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'faculty_id' => 'sometimes|exists:faculties,id',
        ]);

        $course->update($validated);
        return response()->json($course);
    }

    // ❌ Видалити курс
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Курс видалено']);
    }
}
