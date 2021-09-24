<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            // 'city' => CityResource::make($this->whenLoaded('city')),

            $this->merge(Arr::except(parent::toArray($request), [
                'id', 'user_id', 'complete'
            ]))
        ];
    }
}
