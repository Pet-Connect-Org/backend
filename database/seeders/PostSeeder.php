<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $userIds = User::pluck('id');

        for ($i = 1; $i < 12; $i++) {
            Post::create([
                'id' => $i,
                'content' => $faker->text(200),
                'user_id' => $userIds->random(),
            ]);
        }
    }
}
