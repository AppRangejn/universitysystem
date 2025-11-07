<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function index()
    {
        return response()->json(Group::with('course')->get());
    }


    public function show($id)
    {
        $group = Group::with('course')->findOrFail($id);
        return response()->json($group);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        $group = Group::create($validated);
        return response()->json($group, 201);
    }


    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'course_id' => 'sometimes|exists:courses,id',
        ]);

        $group->update($validated);
        return response()->json($group);
    }


    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();

        return response()->json(['message' => 'Групу видалено']);
    }
}
