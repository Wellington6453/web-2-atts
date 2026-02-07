<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    /**
     * Atributos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'title',
        'author_id',
        'publisher_id',
        'category_id',
        'published_year',
        'cover_image',
    ];

    /**
     * Boot method - registra eventos do model
     */
    protected static function boot()
    {
        parent::boot();

        // Evento: antes de deletar o livro
        static::deleting(function ($book) {
            // Remove a imagem do storage se existir
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
        });
    }

    /**
     * Relacionamento: Livro pertence a um Autor
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Relacionamento: Livro pertence a uma Editora
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * Relacionamento: Livro pertence a uma Categoria
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relacionamento muitos-para-muitos com usuários (empréstimos)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrowings')
            ->withPivot('id', 'borrowed_at', 'returned_at')
            ->withTimestamps();
    }
}
