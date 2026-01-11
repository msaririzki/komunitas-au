<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $indoText = [
            'Halo semuanya! Ada yang tahu tempat nongkrong asik di Jakarta Selatan? Lagi butuh referensi nih buat weekend.',
            'Baru saja mencoba resep nasi goreng kambing yang viral itu. Ternyata rasanya luar biasa! Bumbunya meresap banget.',
            'Selamat pagi komunitas! Jangan lupa olahraga hari ini ya, kesehatan itu mahal harganya.',
            'Lagi belajar coding Laravel, ternyata seru juga ya walaupun kadang bikin pusing kepala. Semangat terus buat teman-teman yang lagi belajar!',
            'Cuaca hari ini panas banget ya, enaknya minum es degan di pinggir jalan. Ada yang mau join?',
            'Baru nonton film terbaru di bioskop, plot twist-nya gila banget! Sangat recommended buat yang suka genre thriller.',
            'Info loker dong gan, buat posisi Frontend Developer. Kalau ada info boleh di-share ya. Makasih!',
            'Iseng foto sunset sore ini, hasilnya lumayan juga. Alam Indonesia emang ngga ada duanya.',
            'Diskusi yuk, menurut kalian skill apa yang paling penting dipelajari di tahun 2026 ini? AI atau Cyber Security?',
            'Macet parah di jalan Sudirman pagi ini. Harap bersabar ya buat yang lagi di jalan.',
            'Akhirnya gajian cair juga! Rencana mau beli gadget baru, ada saran HP mid-range yang kameranya bagus?'
        ];

        return [
            'user_id' => User::factory(),
            'content' => $this->faker->randomElement($indoText) . ' ' . $this->faker->optional(0.7)->randomElement($indoText),
            'created_at' => $this->faker->dateTimeBetween('-1 day', 'now'),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            // Add 1-4 random images to each post
            $numImages = rand(1, 4);
            for ($i = 0; $i < $numImages; $i++) {
                $post->images()->create([
                    'image_path' => 'https://picsum.photos/seed/' . rand(1, 1000) . '/800/600'
                ]);
            }
        });
    }
}
