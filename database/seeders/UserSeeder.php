<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kelvin Lihandy',
            'email' => 'kelvinlihandy2005@gmail.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'is_merchant' => false,
        ]);
        User::factory(2)->create();
        User::factory(3)->merchant()->create();
    }
}
