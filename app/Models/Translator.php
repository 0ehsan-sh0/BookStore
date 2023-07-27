<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Translator extends Model
{
    use HasFactory, SoftDeletes;

    // ---------------------------------------------------------------- Relationships

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_translator');
    }

    // Relationships ----------------------------------------------------------------
}
