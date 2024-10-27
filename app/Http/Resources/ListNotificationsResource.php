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
        $imageSrc = !empty($this->getMedia('notification_image')->first()) ? $this->getMedia('notification_image')->first()->getUrl() : env('APP_URL').'assets/img/no-image.png';
        $docSrc = !empty($this->getMedia('notificaiton_doc')->first()) ? $this->getMedia('notificaiton_doc')->first()->getUrl() : '';
        return $data = [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'image' => $imageSrc,
            'document' => $docSrc,
            'youtube_url' => $this->youtube_url,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
