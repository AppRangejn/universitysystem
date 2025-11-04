<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // 🧾 Вивести всіх користувачів
    public function index()
    {
        return response()->json(User::with('group')->get());
    }

    // 👁️ Переглянути конкретного користувача
    public function show($id)
    {
        $user = User::with('group')->findOrFail($id);
        return response()->json($user);
    }

    // ➕ Створити користувача (через адмінку)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,teacher,student,guest',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json($user->load('group'), 201);
    }

    // ✏️ Оновити користувача (адмін)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|string|in:admin,teacher,student,guest',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user->load('group'));
    }

    // ❌ Видалити користувача
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Користувача видалено']);
    }

    // 🧑‍🎓 Оновити власний профіль користувача
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'patronymic' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        // 📸 Оновлення фото
        if ($request->hasFile('photo')) {
            // видаляємо старе фото, якщо існує
            if ($user->photo && file_exists(public_path($user->photo))) {
                @unlink(public_path($user->photo));
            }

            $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('photos'), $filename);
            $validated['photo'] = 'photos/' . $filename;
        }

        $user->update($validated);

        // 🔥 Ключ: перевантажуємо користувача з групою, щоб не злітала
        $user = User::with('group')->find($user->id);

        return response()->json([
            'message' => '✅ Профіль оновлено успішно',
            'user' => $user
        ]);
    }

    // 🔑 Зміна пароля користувача
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Невірний поточний пароль'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Пароль успішно змінено']);
    }

    // 🏫 Призначити студенту групу (через адмінку)
    public function assignGroup(Request $request, User $user)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::findOrFail($request->group_id);

        $user->group_id = $group->id;
        $user->save();

        return response()->json([
            'message' => '✅ Студента призначено до групи ' . $group->name,
            'user' => $user->load('group'),
        ]);
    }
}
