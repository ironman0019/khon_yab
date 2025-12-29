<?php

namespace App\Http\Controllers\Laboratory;

use App\Enums\BloodRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\StoreBloodRequestRequest;
use App\Models\BloodRequest;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BloodRequestController extends Controller
{
    /**
     * Display a listing of the laboratory's blood requests.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = BloodRequest::where('requested_by', $user->id)
            ->with(['province', 'city', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('medical_center', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $bloodRequests = $query->latest()->paginate(15)->withQueryString();

        return view('laboratory.blood-requests.index', compact('bloodRequests'));
    }

    /**
     * Show the form for creating a new blood request.
     */
    public function create(): View
    {
        $user = Auth::user();
        $laboratory = $user->laboratory;

        if (! $laboratory) {
            abort(404, __('laboratory.Laboratory profile not found.'));
        }

        $provinces = Province::all();
        $cities = $laboratory->province_id
            ? \App\Models\City::where('province_id', $laboratory->province_id)->get()
            : collect();

        return view('laboratory.blood-requests.create', compact('provinces', 'cities', 'laboratory'));
    }

    /**
     * Store a newly created blood request in storage.
     */
    public function store(StoreBloodRequestRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $bloodRequest = BloodRequest::create([
            'requested_by' => $user->id,
            'blood_type' => $request->blood_type,
            'rh_factor' => $request->rh_factor,
            'number_of_bags' => $request->number_of_bags,
            'patient_name' => $request->patient_name,
            'patient_age' => $request->patient_age,
            'request_reason' => $request->request_reason,
            'contact_number' => $request->contact_number,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'medical_center' => $request->medical_center,
            'status' => BloodRequestStatus::Pending->value,
            'notes' => $request->notes,
        ]);

        // Notify admins about new blood request
        $this->notifyAdmins($bloodRequest);

        return redirect()->route('laboratory.blood-requests.index')
            ->with('success', __('laboratory.Blood request created successfully.'));
    }

    /**
     * Display the specified blood request.
     */
    public function show(BloodRequest $bloodRequest): View
    {
        $user = Auth::user();

        if ($bloodRequest->requested_by !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        $bloodRequest->load([
            'requestedBy:id,full_name,email',
            'approvedBy:id,full_name,email',
            'province',
            'city',
        ]);

        return view('laboratory.blood-requests.show', compact('bloodRequest'));
    }

    /**
     * Print the blood request receipt.
     */
    public function print(BloodRequest $bloodRequest): View
    {
        $user = Auth::user();

        if ($bloodRequest->requested_by !== $user->id) {
            abort(403, __('laboratory.Unauthorized access.'));
        }

        // Only allow printing approved or completed requests
        if (! in_array($bloodRequest->status, [BloodRequestStatus::Approved->value, BloodRequestStatus::Completed->value])) {
            return redirect()->route('laboratory.blood-requests.show', $bloodRequest)
                ->with('error', __('laboratory.Only approved or completed requests can be printed.'));
        }

        $bloodRequest->load([
            'requestedBy:id,full_name,email',
            'approvedBy:id,full_name,email',
            'province',
            'city',
        ]);

        return view('laboratory.blood-requests.print', compact('bloodRequest'));
    }

    /**
     * Notify all admin users about a new blood request.
     */
    protected function notifyAdmins(BloodRequest $bloodRequest): void
    {
        $admins = User::where('is_admin', true)->get();

        // For now, we'll just log the notification
        // In a real application, you might want to use Laravel's notification system
        Log::info('New blood request created', [
            'request_id' => $bloodRequest->id,
            'requested_by' => $bloodRequest->requested_by,
            'patient_name' => $bloodRequest->patient_name,
            'blood_type' => $bloodRequest->blood_type,
            'number_of_bags' => $bloodRequest->number_of_bags,
        ]);
    }
}
