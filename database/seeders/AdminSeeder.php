<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Dev',
            'email' => 'denki.kiki@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}
