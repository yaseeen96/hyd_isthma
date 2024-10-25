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
        $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : '' ;
        $speaker_details = ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first() ? ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first() : '';
        return [
            'id' => $this->id,
            'theme_name' => empty($lang) ? $this->theme_name : $this->{$lang . '_theme_name'},
            'session_convener' => empty($lang) ? $this->convener : ($speaker_details ? $speaker_details->{$lang . '_name'} : ''),
            'convener_bio' => empty($lang) ? '' : ($speaker_details->bio ? $speaker_details->{$lang . '_bio'} : ''),
            'theme_type' => ucfirst($this->theme_type),
            'hall_name' => $this->hall_name,
            'datetime' => date('Y-m-d', strtotime($this->date)) . ' ' . Carbon::parse($this->from_time)->format('h:i A'). ' - ' . Carbon::parse($this->to_time)->format('h:i A'),
            'status' => $this->status,
            'programs' => Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->count() > 0 ?
                          Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->get()->map(function($program) {
                            return ProgramListResource::make($program);
                          }) : [],
        ];
    }
}