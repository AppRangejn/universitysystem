<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'surname' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'patronymic' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
            'role' => 'in:admin,teacher,student,guest',
            'photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Невірні дані для входу'], 401);
        }

        // ✅ Перевіряємо, чи є сесія
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return response()->json([
            'user' => auth()->user(),
            'message' => 'Успішний вхід',
        ]);
    }



    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Вихід виконано']);
    }
}
