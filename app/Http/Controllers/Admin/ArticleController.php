<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel di dashboard admin.
     * Jika ada query 'search', filter berdasarkan title atau body.
     * Hasil diurutkan terbaru dan dipaginasi 10 per halaman.
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
            ->paginate(10)
            ->withQueryString();

        return view('admin.articles.index', compact('articles', 'search'));
    }

    /**
     * Menampilkan form untuk membuat artikel baru.
     */
    public function create(): View
    {
        return view('admin.articles.create');
    }

    /**
     * Menyimpan artikel baru ke database.
     * Memvalidasi input, mengupload gambar jika ada, lalu menyimpan data.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Proses upload gambar jika ada file yang dikirim
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        // Tambahkan user_id dari admin yang sedang login
        $validated['user_id'] = auth()->id();

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Menampilkan form edit artikel yang sudah ada.
     */
    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Menyimpan perubahan artikel ke database.
     * Jika ada gambar baru, gambar lama dihapus dari storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        // Jika admin mengupload gambar baru
        if ($request->hasFile('image')) {
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diupdate!');
    }

    /**
     * Menghapus artikel dari database.
     * Gambar yang terkait juga dihapus dari storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }
}
