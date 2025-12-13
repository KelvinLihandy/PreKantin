<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::factory(10)->create();
        $merchant_id = User::where('email', 'bconnect404@gmail.com')->first()->merchant->merchant_id;
        $merchant = Merchant::find($merchant_id);

        for ($i = 1; $i <= 5; $i++) {
            MenuItem::create([
                'merchant_id' => $merchant->merchant_id,
                'image' => 'images/dummyMenu.png',
                'name' => "Menu testing " . $i,
                'price' => 10000,
                'is_available' => true,
            ]);
        }
    }
}
