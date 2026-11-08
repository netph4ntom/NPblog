<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel articles baru ke database.
     * Kolom: id, user_id (admin pemilik artikel), title, slug,
     *         image, body, timestamps (created_at & updated_at)
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (admin yang membuat artikel)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');

            // Slug untuk URL yang SEO-friendly, contoh: "judul-artikel-ini"
            $table->string('slug')->unique();

            // Path gambar yang disimpan di storage/app/public
            $table->string('image')->nullable();

            // Isi artikel dalam format teks panjang
            $table->longText('body');

            $table->timestamps();
        });
    }

    /**
     * Rollback: hapus tabel articles jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
