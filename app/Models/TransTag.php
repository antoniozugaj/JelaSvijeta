<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransTag extends Model
{
    use HasFactory;

    public function language()
    {
        return $this->belongsTo(Languaget::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

}
