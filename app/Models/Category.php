<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    public function food()
    {
        return $this->hasOne(Food::class);
    }

    public function trans_category()
    {
        return $this->hasOne(Trans_category::class);
    }
}
