<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        // seeder room type
        RoomType::create([
            'name' => 'Ruang Kelas',
            'total_area' => 230,
            'ownership_status' => true,
            'condition_status' => true,
            'utilization' => 20,
        ]);
    }
}
