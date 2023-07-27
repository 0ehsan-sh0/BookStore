<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

        // ---------------------------------------------------------------- Relationships

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
    
        public function books(): BelongsToMany
        {
            return $this->belongsToMany(Book::class, 'book_cart');
        }
    
        // Relationships ----------------------------------------------------------------
}
