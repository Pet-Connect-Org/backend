<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        $userIds = User::pluck('id');
        $postIds = Post::pluck('id');

        for ($i = 1; $i < 32; $i++) {
            Comment::create([
                'id' => $i,
                'content' => $faker->text(200),
                'user_id' => $userIds->random(),
                'post_id' => $postIds->random()
            ]);
        }
    }
}
