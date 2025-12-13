<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Merchant dengan kredensial tetap (untuk login saat dev)
        $user = User::create([
            'name' => 'Bakmi Egatte',
            'email' => 'bconnect404@gmail.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'is_merchant' => true,
        ]);

        Merchant::create([
            'user_id' => $user->user_id,
            'image' => 'images/dummyKantin.png',
            'open' => '08:00',
            'close' => '17:00',
        ]);

        // Buat beberapa merchant tambahan berdasarkan user merchant yang dibuat oleh UserSeeder
        Merchant::factory(3)->create();
    }
}
