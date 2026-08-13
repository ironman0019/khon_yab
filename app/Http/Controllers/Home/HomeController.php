<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\StoreContactMessageRequest;
use App\Models\BloodRequest;
use App\Models\ContactMessage;
use App\Models\Province;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_logo' => Setting::get('site_logo'),
        ];

        return view('home.index', compact('settings'));
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
            'site_email' => Setting::get('site_email'),
            'site_phone' => Setting::get('site_phone'),
            'site_address' => Setting::get('site_address'),
        ];

        return view('home.contact', compact('settings'));
    }

    /**
     * Store a contact form submission.
     */
    public function storeContact(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::create([
            ...$request->validated(),
            'is_read' => false,
            'read_at' => null,
        ]);

        return redirect()
            ->route('home.contact')
            ->with('success', __('home.Your message has been sent successfully. We will get back to you soon.'));
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name')),
        ];

        return view('home.about', compact('settings'));
    }

    /**
     * Display the blood request search dashboard.
     */
    public function search(Request $request): View
    {
        $query = BloodRequest::with(['province', 'city', 'requestedBy:id,full_name,email'])
            ->whereIn('status', [0, 1, 3]); // Pending, Approved, and Completed requests (not rejected)

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Filter by city
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        $bloodRequests = $query->latest()->paginate(12)
            ->appends($request->only(['blood_type', 'province_id', 'city_id']));

        $provinces = Province::with('cities')->orderBy('name')->get();

        return view('home.search', compact('bloodRequests', 'provinces'));
    }
}
