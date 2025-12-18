<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the donor dashboard.
     */
    public function index(): View
    {
        return view('donor.dashboard.index');
    }
}
