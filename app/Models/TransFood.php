<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransFood extends Model
{
    use HasFactory;

    public function language()
    {
        return $this->belongsTo(Languaget::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
