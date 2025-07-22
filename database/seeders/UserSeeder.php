<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'ajib@stiesia.ac.id',
            'name' => 'Muhammad Mustajib',
            'password' => Hash::make('password')
        ]);

        User::create([
            'email' => 'masum@stiesia.ac.id',
            'name' => 'Muhammad Pixel',
            'password' => Hash::make('masum')
        ]);
    }
}
