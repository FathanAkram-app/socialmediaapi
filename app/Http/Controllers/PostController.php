<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\AddPostRequest;
use App\Http\Requests\Post\EditPostRequest;
use App\Http\Requests\Post\AddCommentRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Models\Comment;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function addPost(AddPostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());

        if ($request->hasFile('images')) {
            $this->postService->uploadImages($post->id, $request->file('images'));
        }

        return new PostResource($post);
    }

    public function editPost(EditPostRequest $request, int $postId)
    {
        $post = $this->postService->updatePost($postId, $request->validated());

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    public function deletePost(int $postId)
    {
        $success = $this->postService->deletePost($postId);

        if (!$success) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['message' => 'Post deleted']);
    }

    public function getAllPosts()
    {
        $posts = $this->postService->getAllPosts();
        return PostResource::collection($posts);
    }

    public function getPostsByUserId(int $userId)
    {
        $posts = $this->postService->getPostsByUserId($userId);
        return PostResource::collection($posts);
    }

    public function getPostsByFollowingUsers()
    {
        $posts = $this->postService->getPostsByFollowingUsers();
        return PostResource::collection($posts);
    }

    public function getSpecificPost(int $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostResource($post);
    }

    public function likePost(int $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        Auth::likedPosts()->attach($postId);

        return response()->json(['message' => 'Post liked']);
    }

    public function unlikePost(int $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        Auth::likedPosts()->detach($postId);

        return response()->json(['message' => 'Post unliked']);
    }

    public function addComment(AddCommentRequest $request, int $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comment = new Comment([
            'text' => $request->text,
            'user_id' => Auth::id(),
            'post_id' => $postId,
        ]);

        $post->comments()->save($comment);

        return new CommentResource($comment);
    }

    public function deleteComment(int $commentId)
    {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }

    public function getMyLikedPosts()
    {
        $user = Auth::user();
        $likedPosts = $user->likedPosts;

        return PostResource::collection($likedPosts);
    }
}
