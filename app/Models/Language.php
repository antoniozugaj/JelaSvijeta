<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;


    public function trans_food()
    {
        return $this->hasMany(Trans_food::class);
    }

    public function trans_tag()
    {
        return $this->hasMany(Trans_tag::class);
    }

    public function trans_ingredient()
    {
        return $this->hasMany(Trans_ingredient::class);
    }

    public function trans_category()
    {
        return $this->hasMany(Trans_category::class);
    }
}
