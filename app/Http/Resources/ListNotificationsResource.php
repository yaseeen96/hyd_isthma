<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListNotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageSrc = !empty($this->getMedia('notification_image')->first()) ? $this->getMedia('notification_image')->first()->getUrl() : '/assets/img/no-image.jpg';
        $docSrc = !empty($this->getMedia('notification_doc')->first()) ? $this->getMedia('notification_doc')->first()->getUrl() : '';
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'image' => $imageSrc,
            'document' => $docSrc,
            'youtube_url' => $this->youtube_url,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
        return $data;
    }
}