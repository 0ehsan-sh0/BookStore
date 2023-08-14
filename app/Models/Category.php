<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'main_category_id', 'url'];

    // ---------------------------------------------------------------- Relationships

    public function main_category()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_category');
    }

    // Relationships ----------------------------------------------------------------
}
