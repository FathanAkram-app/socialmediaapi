<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostRepository
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function findById(int $id): ?Post
    {
        return Post::find($id);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post): bool
    {
        return $post->delete();
    }

    public function getAllPosts(): \Illuminate\Database\Eloquent\Collection
    {
        return Post::all();
    }

    public function getPostsByUserId(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Post::where('user_id', $userId)->get();
    }

     public function getPostsByFollowingUsers(): \Illuminate\Database\Eloquent\Collection
    {
        $followingIds = Auth::following()->pluck('following_id');
        return Post::whereIn('user_id', $followingIds)->latest()->get();
    }
}
