<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create(['name' => 'DITERIMA']); //order received by merchant
        Status::create(['name' => 'DISIAPKAN']); //order prepared by merchant
        Status::create(['name' => 'KONFIRMASI']); //order completed, waiting for user confirmation
        Status::create(['name' => 'SELESAI']); //order completed
        Status::create(['name' => 'DITOLAK']); //order rejected by merchant
    }
}
