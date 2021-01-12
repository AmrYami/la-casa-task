<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'first_name' => 'amr',
                'last_name' => 'amr',
                'country_code' => 'EG',
                'phone_number' => '01011426241',
                'gender' => 'male',
                'email' => 'amr@gmail.com',
                'password'=> Hash::make('123456789')
            ],
            [
                'first_name' => 'yami',
                'last_name' => 'yami',
                'country_code' => 'EG',
                'phone_number' => '01011426242',
                'gender' => 'male',
                'email' => 'yami@gmail.com',
                'password'=> Hash::make('123456789')
            ]
        ];
        User::insert($data);
    }
}
