<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_admin_article_index(): void
    {
        $response = $this->get('/admin/articles');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_admin_article_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/articles');

        $response->assertStatus(200);
        $response->assertSee('Daftar Artikel');
    }

    public function test_admin_dashboard_redirects_to_articles_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertRedirect(route('admin.articles.index'));
    }

    public function test_authenticated_user_can_create_an_article(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $image = UploadedFile::fake()->create('post.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post('/admin/articles', [
            'title' => 'Judul Artikel Baru',
            'body' => 'Konten isi artikel yang sangat menarik dan edukatif.',
            'image' => $image,
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'title' => 'Judul Artikel Baru',
            'slug' => 'judul-artikel-baru',
            'user_id' => $user->id,
        ]);

        $article = Article::where('title', 'Judul Artikel Baru')->first();
        $this->assertNotNull($article->image);
        Storage::disk('public')->assertExists($article->image);
    }

    public function test_article_validation_requires_title_and_body(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/articles', [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    public function test_duplicate_titles_generate_unique_slugs_safely(): void
    {
        $user = User::factory()->create();

        $article1 = Article::create([
            'user_id' => $user->id,
            'title' => 'Tutorial Laravel',
            'body' => 'Konten bagian pertama.',
        ]);

        $article2 = Article::create([
            'user_id' => $user->id,
            'title' => 'Tutorial Laravel',
            'body' => 'Konten bagian kedua.',
        ]);

        $this->assertEquals('tutorial-laravel', $article1->slug);
        $this->assertEquals('tutorial-laravel-1', $article2->slug);
    }

    public function test_authenticated_user_can_update_an_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Judul Lama',
            'body' => 'Isi lama artikel.',
        ]);

        $response = $this->actingAs($user)->put("/admin/articles/{$article->id}", [
            'title' => 'Judul Baru Diperbarui',
            'body' => 'Isi artikel setelah direvisi.',
        ]);

        $response->assertRedirect(route('admin.articles.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Baru Diperbarui',
            'slug' => 'judul-baru-diperbarui',
        ]);
    }

    public function test_authenticated_user_can_delete_an_article(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('thumbnail.png', 50, 'image/png');
        $path = $file->store('articles', 'public');

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'image' => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->delete("/admin/articles/{$article->id}");

        $response->assertRedirect(route('admin.articles.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
