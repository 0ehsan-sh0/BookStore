<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory, SoftDeletes;

        // ---------------------------------------------------------------- Relationships

        public function writer()
        {
            return $this->belongsTo(Writer::class, 'writer_id');
        }
    
        public function translators(): BelongsToMany
        {
            return $this->belongsToMany(Translator::class, 'book_translator');
        }
    
        public function categories(): BelongsToMany
        {
            return $this->belongsToMany(Category::class, 'book_category');
        }
    
        public function carts(): BelongsToMany
        {
            return $this->belongsToMany(Cart::class, 'book_cart');
        }
        
        public function comments()
        {
            return $this->hasMany(Comment::class, 'book_id');
        }
    
        // Relationships ----------------------------------------------------------------
    
}
