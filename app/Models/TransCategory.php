<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransCategory extends Model
{
    use HasFactory;

    public function language()
    {
        return $this->belongsTo(Languaget::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
