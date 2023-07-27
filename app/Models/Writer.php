<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Writer extends Model
{
    use HasFactory, SoftDeletes;

    // ---------------------------------------------------------------- Relationships

    public function books(){
        return $this->hasMany(Book::class, 'writer_id');
    }

    // Relationships ----------------------------------------------------------------
}
