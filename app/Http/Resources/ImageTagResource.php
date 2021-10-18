<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageTagResource extends JsonResource
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
            'label' => $this->label,
            $this->additional_field => $this->additional_value,
            'coordinates' => [
                [$this->x_1, $this->y_1],
                [$this->x_2, $this->y_2],
                [$this->x_3, $this->y_3],
                [$this->x_4, $this->y_4]
            ]
        ];
    }
}
