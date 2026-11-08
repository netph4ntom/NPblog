<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicArticleController extends Controller
{
    /**
     * Menampilkan home page dengan daftar artikel.
     * Jika ada query 'search', filter berdasarkan title atau body.
     * Artikel ditampilkan 6 per halaman (sesuai grid wireframe).
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $articles = Article::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('body', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(6) // 6 artikel per halaman sesuai grid 3x2
            ->withQueryString();

        return view('home', compact('articles', 'search'));
    }

    /**
     * Menampilkan detail satu artikel berdasarkan slug.
     * Slug digunakan agar URL lebih SEO-friendly.
     * Contoh: /articles/cara-belajar-laravel
     */
    public function show(string $slug): View
    {
        // Cari artikel by slug, tampilkan 404 otomatis jika tidak ditemukan
        // Eager load user untuk menghindari N+1 query
        $article = Article::with('user')->where('slug', $slug)->firstOrFail();

        return view('articles.show', compact('article'));
    }
}
