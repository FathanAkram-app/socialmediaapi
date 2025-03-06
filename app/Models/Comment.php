<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'text',
    ];

    // Relasi ke Post (Belongs To)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

     // Relasi ke User (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
