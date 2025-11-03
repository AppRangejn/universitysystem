<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSetting;
use Illuminate\Http\Request;

class ScheduleSettingController extends Controller
{
    // 🧾 Отримати поточні налаштування
    public function index()
    {
        $settings = ScheduleSetting::first();

        // Якщо ще не створено — створюємо стандартні
        if (!$settings) {
            $settings = ScheduleSetting::create([
                'days' => ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця', 'Субота', 'Неділя'],
                'pair_count' => 6,
            ]);
        }

        return response()->json($settings);
    }

    // 💾 Оновити налаштування
    public function update(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'string|max:50',
            'pair_count' => 'required|integer|min:1|max:12',
        ]);

        $settings = ScheduleSetting::first();
        if (!$settings) {
            $settings = ScheduleSetting::create($validated);
        } else {
            $settings->update($validated);
        }

        return response()->json($settings);
    }
}
