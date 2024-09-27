<?php

namespace App\Http\Resources;

use App\Models\ProgramRegistration;
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
            'datetime' => $this->date ? date('Y-m-d', strtotime($this->date)) : 'NA' . ' ' . Carbon::parse($this->time)->format('H:i A'). '-' . Carbon::parse($this->end_time)->format('H:i A'),
            'session_theme' => $this->sessionTheme->theme_name,
            'session_convener' => $this->sessionTheme->convener,
            'theme_type' => $this->sessionTheme->theme_type,
            'speaker_name' => $this->programSpeaker->name,
            'speaker_bio' => $this->programSpeaker->bio,
            'speaker_image' => $this->programSpeaker->getMedia('speaker_image')->first() ? $this->programSpeaker->getMedia('speaker_image')->first()->getUrl() : '/assets/img/no-image.png',
            'enrolled' => ProgramRegistration::where('program_id', $this->id)->where('member_id', auth()->id())->exists()
        ];
        return $data;
    }
}
