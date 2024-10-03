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
        return [
            'id' => $this->id,
            'theme_name' => $this->theme_name,
            'session_convener' => $this->convener,
            'theme_type' => ucfirst($this->theme_type),
            'hall_name' => $this->hall_name,
            'convener_bio' => ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first() ? ProgramSpeaker::where('name', 'LIKE', '%' . $this->convener . '%')->first()->bio : '',
            'datetime' => date('Y-m-d', strtotime($this->date)) . ' ' . Carbon::parse($this->from_time)->format('h:i A'). ' - ' . Carbon::parse($this->to_time)->format('h:i A'),
            'status' => $this->status,
            'programs' => Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->count() > 0 ?
                          Program::with('sessionTheme', 'programSpeaker')->where('session_theme_id', $this->id)->get()->map(function($program) {
                            return ProgramListResource::make($program);
                          }) : []
        ];
    }
}