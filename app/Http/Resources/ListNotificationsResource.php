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

        $include = false;
        $user = auth()->user();
        $confirmArrival = !empty($user->registration) ? $user->registration->confirm_arrival : 0;
        if($this->criteria['region_type']  == 'unit' &&  $user->unit_name == $this->criteria['region_value']) {
            $include = true;
        }
        if($this->criteria['region_type']  == 'zone' &&  $user->unit_name == $this->criteria['region_value']) {
            $include = true;
        }
        if($this->criteria['region_type']  == 'division' &&  $user->unit_name == $this->criteria['region_value']) {
            $include = true;
        }
        if(!empty($this->criteria['reg_status'])){
            $include = ($this->criteria['reg_status'] ==  $confirmArrival && $include) ? true : false;
        }
        if(!empty($this->criteria['gender'])){
            $include = ($this->criteria['gender'] == strtolower($user->gender) && $include) ? true: false;
        }
        if($include) {
            $imageSrc = !empty($this->getMedia('notification_image')->first()) ? $this->getMedia('notification_image')->first()->getUrl() : env('APP_URL').'assets/img/no-image.png';
            $docSrc = !empty($this->getMedia('notificaiton_doc')->first()) ? $this->getMedia('notificaiton_doc')->first()->getUrl() : '';
            $data = [
                'id' => $this->id,
                'title' => $this->title,
                'message' => $this->message,
                'image' => $imageSrc,
                'document' => $docSrc,
                'youtube_url' => $this->youtube_url,
                'created_at' => date('d-m-Y', strtotime($this->created_at)),
            ];
        }
        return $data ?? [];
    }
}
