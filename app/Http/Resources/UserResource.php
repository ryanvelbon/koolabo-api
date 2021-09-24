<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'profile' => UserProfileResource::make($this->whenLoaded('profile')),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'topics' => TopicResource::collection($this->whenLoaded('topics')),

            $this->merge(Arr::except(parent::toArray($request), [
                'created_at', 'updated_at', 'email', 'email_verified_at'
            ]))
        ];
    }
}
