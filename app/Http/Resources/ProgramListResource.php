<?php

namespace App\Http\Resources;

use App\Models\ProgramRegistration;
use App\Models\ProgramSpeaker;
use App\Models\SessionRegistration;
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
        $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : '' ;
        $data = [
            'id' => $this->id,
            'name' => empty($lang) ? $this->topic : $this->{$lang . '_topic'},
            'url' => empty($lang) ? $this->url : $this->{$lang . '_url'},
            'datetime' => !empty($this->from_time) && !empty($this->from_time) ? ( date('Y-m-d', strtotime($this->date)) . ' ' . Carbon::parse($this->from_time)->format('h:i A'). ' - ' . Carbon::parse($this->to_time)->format('h:i A') ) : "NA",
            'speaker_name' => !empty($this->program_speaker_id) ? ( empty($lang) ? $this->programSpeaker->name : $this->programSpeaker->{$lang . '_name'}) : null,
            'speaker_bio' => !empty($this->program_speaker_id) ? (empty($lang) ? $this->programSpeaker->bio : $this->programSpeaker->{$lang . '_bio'}) : null,
            'speaker_image' => !empty($this->program_speaker_id) ? ($this->programSpeaker->getMedia('speaker_image')->first() ? $this->programSpeaker->getMedia('speaker_image')->first()->getUrl() : env('APP_URL').'/assets/img/no-image.png') : null,
            'status' => $this->status,
            'translation' => empty($lang) ? null : ( $this->getMedia($lang.'_translation')->first() ? $this->getMedia($lang.'_translation')->first()->getUrl() : null),
            'transcript' => empty($lang) ? null : $this->{$lang.'_transcript'},
        ];
        return $data;
    }
}