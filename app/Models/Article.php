<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     * Melindungi kolom lain dari input tidak sah.
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'image',
        'body',
    ];

    /**
     * Auto-generate slug unik dari title setiap kali artikel dibuat/diupdate.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $article) {
            if (empty($article->slug)) {
                $article->slug = static::generateUniqueSlug($article->title);
            }
        });

        static::updating(function (self $article) {
            if ($article->isDirty('title')) {
                $article->slug = static::generateUniqueSlug($article->title, $article->id);
            }
        });
    }

    /**
     * Menghasilkan slug yang unik untuk mencegah error duplicate key di database.
     * Contoh: "Judul Sama" -> "judul-sama", lalu "judul-sama-1", dst.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if (blank($baseSlug)) {
            $baseSlug = 'artikel-'.Str::random(6);
        }

        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Estimasi waktu membaca dalam menit berdasarkan jumlah kata.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->body ?? ''));

        return max(1, (int) round($wordCount / 200));
    }

    /**
     * URL publik untuk gambar artikel.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        return $this->image ? Storage::url($this->image) : null;
    }

    /**
     * Relasi: setiap artikel dimiliki oleh satu user (admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
