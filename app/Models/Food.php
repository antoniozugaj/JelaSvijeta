<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
	
	public function ingredient()
    {
        return $this->belongsToMany(Ingredient::class);
    }
	
	public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }
	
	 public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function trans_food()
    {
        return $this->hasOne(Trans_food::class);
    }

}
