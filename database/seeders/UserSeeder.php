<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'tamiyah@cocohub.ph'],
            [
                'role_id'  => 1,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'raniel@cocohub.ph'],
            [
                'role_id'  => 2, 
                'password' => Hash::make('password'),
            ]
        );
    }
}