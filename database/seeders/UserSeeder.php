<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name'  => 'Admin',
            'email' => 'admin@app.com',
            'password'  => Hash::make('123456'),
            'role'  => 'admin',
            'image' => '/app/public/users/avatar.png'
        ]);
    }
}
