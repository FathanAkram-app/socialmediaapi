<?php

namespace App\Services;

use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPost(array $data)
    {
        $data['user_id'] = Auth::id(); // Set user_id secara otomatis
        return $this->postRepository->create($data);
    }

    public function updatePost(int $postId, array $data)
    {
        $post = $this->postRepository->findById($postId);

        if (!$post) {
            return null;
        }

        return $this->postRepository->update($post, $data);
    }

    public function deletePost(int $postId)
    {
        $post = $this->postRepository->findById($postId);

        if (!$post) {
            return false;
        }

        // Delete images related to the post (jika ada)
        foreach ($post->images as $image) {
            Storage::delete($image->path); // Hapus file dari storage
            $image->delete(); // Hapus record dari database
        }

        return $this->postRepository->delete($post);
    }

    public function getAllPosts()
    {
        return $this->postRepository->getAllPosts();
    }

    public function getPostsByUserId(int $userId)
    {
        return $this->postRepository->getPostsByUserId($userId);
    }

    public function getPostsByFollowingUsers()
    {
        return $this->postRepository->getPostsByFollowingUsers();
    }

    public function uploadImages(int $postId, array $images)
    {
        $post = $this->postRepository->findById($postId);

        if (!$post) {
            return null;
        }

        foreach ($images as $image) {
            $path = $image->store('posts'); // Simpan gambar di storage/app/posts
            $post->images()->create(['path' => $path]);
        }

        return $post;
    }
}
