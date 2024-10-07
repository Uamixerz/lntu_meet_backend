<?php

namespace App\Http\Resources\user;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'telegramID' => $this->telegramID,
            'images' => $this->images->pluck('image_path'),
            'about' => $this->about,
            'age' => $this->age,
            'faculty' => $this->faculty_id,
            'course' => $this->course,
            'phone' => $this->phone,
        ];
    }
}
