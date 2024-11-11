<?php

namespace App\Http\View\Composers;

use App\Models\Member;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GlobalComposer
{
    protected $locationsList;

    public function __construct()
    {
        // Fetch global data
        $this->locationsList = Cache::remember('locationsList', 60 * 60, function () {
            return [
                'distnctUnitName' => Member::select('unit_name')->filterByZone()->distinct()->get(),
                'distnctZoneName' => Member::select('zone_name')->filterByZone()->distinct()->get(),
                'distnctDivisionName' => Member::select('division_name')->filterByZone()->distinct()->get(),
            ];
        });
    }

    public function compose(View $view)
    {
        // Attach data to the view
        $view->with('locationsList', $this->locationsList);
    }
}