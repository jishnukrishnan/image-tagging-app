<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'url' => request()->getHttpHost() . '/' . $this->getPathWithUid(),
            'category'=>$this->category,
            'height' => $this->height,
            'width' => $this->width,
            'public' => $this->public,
            'created_at' => $this->created_at,
            'tags' => ImageTagResource::collection($this->whenLoaded('tags')),
            'links' => [
                'self' => request()->getHttpHost().'/api/images/'.$this->id
            ]
        ];
    }
}
