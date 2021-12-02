<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
	
	public function food(){
		
		return $this->belongsToMany(Food::class);
	}

	public function trans_tag()
    {
        return $this->hasOne(Trans_tag::class);
    }
}
