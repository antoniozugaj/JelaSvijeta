<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
	
	public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
	
	public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
	
	 public function category()
    {
        return $this->hasOne(Category::class);
    }
}
