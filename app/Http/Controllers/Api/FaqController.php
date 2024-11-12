<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqsListResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function listFaqs()
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        return response()->json([
            'data' => FaqsListResource::collection($faqs),
        ]);
    }
}