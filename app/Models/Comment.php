<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['comment', 'user_id', 'book_id', 'article_id'];

    // ---------------------------------------------------------------- Relationships

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relationships ----------------------------------------------------------------
}
