<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ArticleResource extends JsonResource
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->body,
            'status' => $this->status,
            'user' => new UserPublicResource($this->user),
            'createdAt' => $this->created_at->format('d-m-Y H:i:s'),
            'updatedAt' => $this->updated_at->format('d-m-Y H:i:s'),
            'created' => $this->created_at->diffForHumans(),
            'updated' => $this->updated_at->diffForHumans(),
        ];
    }
}
