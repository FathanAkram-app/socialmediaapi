<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'path',
    ];

    // Relasi ke Post (Belongs To)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
