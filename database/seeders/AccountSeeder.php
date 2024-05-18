<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Account::insert([
            [
                'id' => 5,
                'email' => 'buithuyngoc1@gmail.com',
                'password' => '$2y$12$PzQVh1IW1pwPn1FG5s6e5usjiF70A8LjfXCpc3IuLFosGG1Fo92/2',
                'role' => 1,
                'isActived' => 1,
                'remember_token' => 'nYpAiQ4tGw'
            ],
            [
                'id' => 6,
                'email' => 'buithuyanh2k5@gmail.com',
                'password' => '$2y$12$mNuWwjtLXZUIefvNe50LEuiqO/ORQ.R3xu/Id/W/TiaGkZDD9DpjK',
                'role' => 1,
                'isActived' => 1,
                'remember_token' => 'fxjAAtRqAU'
            ]
        ]);
    }
}
