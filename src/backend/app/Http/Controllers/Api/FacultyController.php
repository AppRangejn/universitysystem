<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{

    public function index()
    {
        return response()->json(
            Faculty::with('courses.groups')->get()
        );
    }


    public function show(Faculty $faculty)
    {
        return response()->json(
            $faculty->load('courses.groups')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $faculty = Faculty::create($validated);
        return response()->json($faculty, 201);
    }


    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $faculty->update($validated);
        return response()->json($faculty);
    }


    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return response()->json(['message' => 'Факультет видалено']);
    }
}
