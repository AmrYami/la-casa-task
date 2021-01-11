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
                'name' => 'amr',
                'email' => 'amr@gmail.com',
                'password'=> Hash::make('123456789')
            ],
            [
                'name' => 'yami',
                'email' => 'yami@gmail.com',
                'password'=> Hash::make('123456789')
            ]
        ];
        User::insert($data);
    }
}
