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
            'program_copy' => $this->getMedia('program_copy')->first() ? $this->getMedia('program_copy')->first()->getUrl() : null,
            'english' => [
                'topic' => $this->english_topic,
                'transcript' => $this->english_transcript,
                'program_copy' => $this->getMedia('english_program_copy')->first() ? $this->getMedia('english_program_copy')->first()->getUrl() : null,
                'translation' => $this->getMedia('english_translation')->first() ? $this->getMedia('english_translation')->first()->getUrl() : null
            ],
            'malyalam' => [
                'topic' => $this->malyalam_topic,
                'transcript' => $this->malyalam_transcript,
                'program_copy' => $this->getMedia('malyalam_program_copy')->first() ? $this->getMedia('malyalam_program_copy')->first()->getUrl() : null,
                'translation' => $this->getMedia('malyalam_translation')->first() ? $this->getMedia('malyalam_translation')->first()->getUrl() : null
            ],
            'bengali' => [
                'topic' => $this->bengali_topic,
                'transcript' => $this->bengali_transcript,
                'program_copy' => $this->getMedia('bengali_program_copy')->first() ? $this->getMedia('bengali_program_copy')->first()->getUrl() : null,
                'translation' => $this->getMedia('bengali_translation')->first() ? $this->getMedia('bengali_translation')->first()->getUrl() : null
            ],
            'tamil' => [
                'topic' => $this->tamil_topic,
                'transcript' => $this->tamil_transcript,
                'program_copy' => $this->getMedia('tamil_program_copy')->first() ? $this->getMedia('tamil_program_copy')->first()->getUrl() : null,
                'translation' => $this->getMedia('tamil_translation')->first() ? $this->getMedia('tamil_translation')->first()->getUrl() : null
            ],
            'kannada' => [
                'topic' => $this->kannada_topic,
                'transcript' => $this->kannada_transcript,
                'program_copy' => $this->getMedia('kannada_program_copy')->first() ? $this->getMedia('kannada_program_copy')->first()->getUrl() : null,
                'translation' => $this->getMedia('kannada_translation')->first() ? $this->getMedia('kannada_translation')->first()->getUrl() : null
            ],
        ];
        return $data;
    }
}