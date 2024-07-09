<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    // Itenary Report
    public function travelReport() {
        return view('admin.reports.travel-report');
    }
}