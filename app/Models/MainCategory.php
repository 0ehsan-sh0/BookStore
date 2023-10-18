<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'url'];

    // ---------------------------------------------------------------- Relationships

    public function categories()
    {
        return $this->hasMany(Category::class, 'main_category_id');
    }

    // Relationships ----------------------------------------------------------------
}
