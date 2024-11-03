<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqsListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attachment = !empty($this->getMedia('faq_attachment')->first()) ? $this->getMedia('faq_attachment')->first()->getUrl() : 'NA';
        $faqUrl = $attachment != 'NA' ? $attachment : 'NA';
        return [
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'faq_attachment' => $faqUrl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}