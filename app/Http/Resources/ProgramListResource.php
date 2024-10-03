<?php

namespace App\Http\Resources;

use App\Models\ProgramRegistration;
use App\Models\ProgramSpeaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->topic,
            'datetime' => date('Y-m-d', strtotime($this->date)) . ' ' . Carbon::parse($this->from_time)->format('h:i A'). ' - ' . Carbon::parse($this->to_time)->format('h:i A'),
            'speaker_name' => $this->programSpeaker->name,
            'speaker_bio' => $this->programSpeaker->bio,
            'speaker_image' => $this->programSpeaker->getMedia('speaker_image')->first() ? $this->programSpeaker->getMedia('speaker_image')->first()->getUrl() : '/assets/img/no-image.png',
            'enrolled' => ProgramRegistration::where('program_id', $this->id)->where('member_id', auth()->id())->exists(),
            'status' => $this->status,
        ];
        return $data;
    }
}