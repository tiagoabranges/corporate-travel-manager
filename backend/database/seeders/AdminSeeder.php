<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // 👈 FALTAVA ISSO

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'tiago',
            'email' => 'tiago@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin'
        ]);
    }
}
