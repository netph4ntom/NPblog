<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'dapit@gmail.com'],
            [
                'name' => 'dapit',
                'password' => Hash::make('password'),
            ]
        );

        if (Article::count() === 0) {
            Article::create([
                'user_id' => $user->id,
                'title' => 'Selamat Datang di NPblog',
                'slug' => 'selamat-datang-di-npblog',
                'body' => "Selamat datang di platform NPblog!\n\nNPblog adalah ruang berbagi cerita, gagasan, dan tulisan berkualitas seputar teknologi, pemrograman, dan wawasan kehidupan sehari-hari.\n\nAnda dapat mengelola artikel melalui dashboard admin yang telah disediakan.",
            ]);
        }
    }
}
