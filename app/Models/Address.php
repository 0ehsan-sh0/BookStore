<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
   use HasFactory, SoftDeletes;
   protected $fillable = ['name','lastname','phone','city', 'state' , 'place_number','post_code', 'address', 'user_id'];

   // ---------------------------------------------------------------- Relationships

   public function user()
   {
      return $this->belongsTo(User::class, 'user_id');
   }

   // Relationships ----------------------------------------------------------------
}
