<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name' => 'Oleg',
            'surname' => 'Malutin',
            'email' => 'oleg@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);


        User::factory(5)->create(['role' => 'teacher']);
        User::factory(20)->create(['role' => 'student']);
        User::factory(5)->create(['role' => 'guest']);
    }
}
