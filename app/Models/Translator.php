<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translator extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'photo'];

    // ---------------------------------------------------------------- Relationships

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_translator');
    }

    // Relationships ----------------------------------------------------------------
}
