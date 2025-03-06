<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
    ];

    // Relasi ke User (Belongs To)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Images (One to Many)
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    // Relasi ke Comment (One to Many)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Relasi ke Like (Many-to-Many)
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id')->withTimestamps();
    }
}
