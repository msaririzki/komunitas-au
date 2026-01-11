<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'parent_id' => null, // Default to top-level comment
            'content' => fake('id_ID')->sentence(rand(3, 10)),
            'created_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }
}
