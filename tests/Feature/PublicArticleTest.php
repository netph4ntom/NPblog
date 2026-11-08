<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Semua');
        $response->assertSee('Artikel');
    }

    public function test_homepage_displays_articles(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Belajar Laravel 12',
            'body' => 'Panduan lengkap belajar framework Laravel.',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    public function test_articles_can_be_filtered_by_search(): void
    {
        $user = User::factory()->create();

        $article1 = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Panduan Docker untuk Pemula',
            'body' => 'Tutorial dasar containerization dengan Docker.',
        ]);

        $article2 = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Tips Produktivitas Programmer',
            'body' => 'Cara menjaga fokus saat coding.',
        ]);

        $response = $this->get('/?search=Docker');

        $response->assertStatus(200);
        $response->assertSee($article1->title);
        $response->assertDontSee($article2->title);
    }

    public function test_article_detail_page_can_be_rendered_via_slug(): void
    {
        $user = User::factory()->create(['name' => 'Dapit']);
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Mengenal Clean Architecture',
            'body' => 'Clean architecture memisahkan business logic dari framework.',
        ]);

        $response = $this->get('/articles/'.$article->slug);

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee('Dapit');
    }

    public function test_article_detail_returns_404_for_nonexistent_slug(): void
    {
        $response = $this->get('/articles/slug-yang-tidak-ada');

        $response->assertStatus(404);
    }
}
