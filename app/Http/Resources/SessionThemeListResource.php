<?php

namespace App\Http\Resources;

use App\Models\Program;
use App\Models\ProgramSpeaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionThemeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $speaker_details = ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first() ? ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first() : '';
        return [
            'id' => $this->id,
            'theme_name' => $this->theme_name,
            'session_convener' => $this->convener,
            'theme_type' => ucfirst($this->theme_type),
            'hall_name' => $this->hall_name,
            'convener_bio' => $speaker_details ? $speaker_details->bio : '',
            'datetime' => date('Y-m-d', strtotime($this->date)) . ' ' . Carbon::parse($this->from_time)->format('h:i A'). ' - ' . Carbon::parse($this->to_time)->format('h:i A'),
            'status' => $this->status,
            'english' => [
                'theme_name' => $this->english_theme_name,
                'session_convener' => $speaker_details ? $speaker_details->english_name : '',
                'convener_bio' => $speaker_details ? $speaker_details->english_bio : '',
            ],
            'malyalam' => [
                'theme_name' => $this->malyalam_theme_name,
                'session_convener' => $speaker_details ? $speaker_details->malyalam_name : '',
                'male_bio' => $speaker_details ? $speaker_details->malyalam_bio : '',
            ],
            'bengali' => [
                'theme_name' => $this->bengali_theme_name,
                'session_convener' => $speaker_details ? $speaker_details->bengali_name : '',
                'bengali_bio' => $speaker_details ? $speaker_details->bengali_bio : '',
            ],
            'tamil' => [
                'theme_name' => $this->tamil_theme_name,
                'session_convener' => $speaker_details ? $speaker_details->tamil_name : '',
                'tamil_bio' => $speaker_details ? $speaker_details->tamil_bio : '',
            ],
            'kannada' => [
                'theme_name' => $this->kannada_theme_name,
                'session_convener' => $speaker_details ? $speaker_details->kannada_name : '',
                'kannada_bio' => $speaker_details ? $speaker_details->kannada_bio : '',
            ],
            'programs' => Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->count() > 0 ?
                          Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->get()->map(function($program) {
                            return ProgramListResource::make($program);
                          }) : []
        ];
    }
}
