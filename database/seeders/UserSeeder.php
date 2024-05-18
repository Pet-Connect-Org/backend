<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::insert([
            [
                'id' => 5,
                'account_id' => 5,
                'name' => 'Bui Thuy Ngoc',
                'sex' => 'female',
                'address' => 'Hà Đông, Hà Nội',
                'birthday' => '2003-02-13'
            ],
            [
                'id' => 6,
                'account_id' => 6,
                'name' => 'Bui Thuy Anh',
                'sex' => 'female',
                'address' => 'Hà Đông, Hà Nội',
                'birthday' => '2005-12-03'
            ]
        ]);
    }
}
