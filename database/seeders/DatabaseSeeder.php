<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create specific Test User
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create 10 Random Users
        $users = User::factory(10)->create();
        $users->push($testUser); // Add test user to the collection for post assignment

        // 2. Create 20 Posts (randomly assigned to users)
        $posts = Post::factory(20)->recycle($users)->create();

        // 3. Create 50 nested comments
        // First, create some top-level comments
        $comments = Comment::factory(30)->recycle($users)->recycle($posts)->create();

        // Then create replies
        Comment::factory(20)->recycle($users)->recycle($posts)->create(function () use ($comments) {
            return [
                'parent_id' => $comments->random()->id,
            ];
        });
    }
}
