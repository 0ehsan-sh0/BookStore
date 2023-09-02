<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['name','url'];

        // ---------------------------------------------------------------- Relationships

        public function books(): BelongsToMany
        {
            return $this->belongsToMany(Book::class, 'book_tag');
        }

        public function articles(): BelongsToMany
        {
            return $this->belongsToMany(Article::class, 'article_tag');
        }
    
        // Relationships ----------------------------------------------------------------
    
}
