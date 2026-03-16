<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BuyerDetail;

class BuyerDetailSeeder extends Seeder
{
    public function run(): void
    {
        $buyer = User::where('email', 'raniel@cocohub.ph')->first();

        if ($buyer) {
            BuyerDetail::updateOrCreate(
            ['user_id' => $buyer->id],
                [
                    'first_name'   => 'Raniel John',
                    'middle_name'  => 'Taqueban',
                    'last_name'    => 'Britos',
                    'age'          => 21,
                    'address'      => 'Dona Aurora Quezon City',
                    'phone_number' => '0912345678',
                    'profile_picture' => null,
                ]
            );
        }
    }
}