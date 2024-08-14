<?php

namespace App\Http\View\Composers;

use App\Models\Member;
use Illuminate\View\View;

class GlobalComposer
{
    protected $locationsList;

    public function __construct()
    {
        // Fetch global data
        $this->locationsList = [
            'distnctUnitName' => Member::select('unit_name')->filterByZone()->distinct()->get(),
            'distnctZoneName' => Member::select('zone_name')->filterByZone()->distinct()->get(),
            'distnctDivisionName' => Member::select('division_name')->filterByZone()->distinct()->get(),
        ];
    }

    public function compose(View $view)
    {
        // Attach data to the view
        $view->with('locationsList', $this->locationsList);
    }
}