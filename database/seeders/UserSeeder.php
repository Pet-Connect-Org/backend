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
                'id' => 1,
                'account_id' => 1,
                'name' => 'Bui Thuy Ngoc',
                'sex' => 'female',
                'address' => 'Hà Đông, Hà Nội',
                'birthday' => '2003-02-13'
            ],
            [
                'id' => 2,
                'account_id' => 2,
                'name' => 'Bui Thuy Anh',
                'sex' => 'female',
                'address' => 'Hà Đông, Hà Nội',
                'birthday' => '2005-12-03'
            ]
        ]);
    }
}
