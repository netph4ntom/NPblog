<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
| Bisa diakses semua user tanpa login
*/

// Homepage → list semua artikel
Route::get('/', [PublicArticleController::class, 'index'])
    ->name('home');

// Detail artikel berdasarkan slug
Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])
    ->name('articles.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
| Hanya bisa diakses setelah login
*/

Route::middleware('auth')->group(function () {

    // Alias dashboard agar kompatibel dengan default Laravel Breeze
    Route::get('/dashboard', function () {
        return redirect()->route('admin.articles.index');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'edit'])
            ->name('edit');

        Route::patch('/', [ProfileController::class, 'update'])
            ->name('update');

        Route::delete('/', [ProfileController::class, 'destroy'])
            ->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    | CRUD artikel untuk management blog
    */

    Route::prefix('admin')->name('admin.')->group(function () {

        // redirect /admin → /admin/articles
        Route::get('/', function () {
            return redirect()->route('admin.articles.index');
        })->name('dashboard');

        // CRUD artikel
        Route::resource('articles', ArticleController::class)
            ->except('show');
    });
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
