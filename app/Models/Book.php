<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'name', 'english_name',
        'description', 'price', 'photo', 'print_series',
        'isbn', 'book_cover_type', 'format', 'pages',
        'publish_year', 'count', 'writer_id', 'publisher',
    ];

    // ---------------------------------------------------------------- Relationships

    public function writer()
    {
        return $this->belongsTo(Writer::class);
    }

    public function translators(): BelongsToMany
    {
        return $this->belongsToMany(Translator::class, 'book_translator');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_user');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'book_cart')->withPivot('count');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tag');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'book_id');
    }

    // Relationships ----------------------------------------------------------------

}
