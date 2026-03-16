<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SellerDetail;

class SellerDetailSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'tamiyah@cocohub.ph')->first();

        if ($seller) {
            SellerDetail::updateOrCreate(
                ['user_id' => $seller->id],
                [
                    'first_name'      => 'Tamiyah Gale',
                    'middle_name'     => 'Cederio',
                    'last_name'       => 'Valera',
                    'age'             => 21,
                    'address'         => 'BGC, Taguig City',
                    'phone_number'    => '0912345678',
                    'profile_picture' => null, 
                ]
            );
        }
    }
}