<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
	
	public function food(){
		
		return $this->belongsToMany(Food::class);
	}

	public function transIngredient()
    {
        return $this->hasOne(TransIngredient::class);
    }
}
