<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'user' => new UserResource($this->user), // Relasi User
            'images' => $this->images->pluck('path'), // Daftar path gambar
            'comments' => CommentResource::collection($this->comments), // Relasi Comment
            'likes_count' => $this->likes()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
