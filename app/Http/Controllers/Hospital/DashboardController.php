<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the hospital dashboard.
     */
    public function index(): View
    {
        return view('hospital.dashboard.index');
    }
}
