<?php

namespace App\Http\Controllers\Admin\DonorManagement;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DonorManagement\StoreDonorRequest;
use App\Http\Requests\Admin\DonorManagement\UpdateDonorRequest;
use App\Models\Donor;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DonorController extends Controller
{
    /**
     * Display a listing of donors.
     */
    public function index(Request $request): View
    {
        $query = Donor::with(['user', 'province', 'city']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('national_code', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->get('blood_type'));
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->get('province_id'));
        }

        // Filter by health status
        if ($request->filled('health_status')) {
            $healthStatus = $request->get('health_status');
            $query->where('health_status', $healthStatus === '1' || $healthStatus === 1);
        }

        // Filter by ability to donate
        if ($request->filled('ability_to_donate')) {
            $abilityToDonate = $request->get('ability_to_donate');
            $query->where('ability_to_donate', $abilityToDonate === '1' || $abilityToDonate === 1);
        }

        $donors = $query->latest()->paginate(15)->withQueryString();
        $provinces = Province::all();

        return view('admin.donor-management.index', compact('donors', 'provinces'));
    }

    /**
     * Show the form for creating a new donor.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('admin.donor-management.create', compact('provinces'));
    }

    /**
     * Store a newly created donor.
     */
    public function store(StoreDonorRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Create user first
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::Donor->value,
            ]);

            // Create donor profile
            Donor::create([
                'user_id' => $user->id,
                'mobile_number' => $request->mobile_number,
                'national_code' => $request->national_code,
                'age' => $request->age,
                'gender' => $request->gender,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'blood_type' => $request->blood_type,
                'rh_factor' => $request->rh_factor,
                'health_status' => $request->boolean('health_status', false),
                'last_donation_date' => $request->last_donation_date,
                'ability_to_donate' => $request->boolean('ability_to_donate', false),
            ]);
        });

        return redirect()->route('admin.donor-management.index')
            ->with('success', __('admin.Donor created successfully.'));
    }

    /**
     * Display the specified donor.
     */
    public function show(Donor $donor_management): View
    {
        $donor_management->load([
            'user',
            'province',
            'city',
            'bloodDonationRecords' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return view('admin.donor-management.show', ['donor' => $donor_management]);
    }

    /**
     * Show the form for editing the specified donor.
     */
    public function edit(Donor $donor_management): View
    {
        $donor_management->load(['user', 'province', 'city']);
        $provinces = Province::all();

        return view('admin.donor-management.edit', ['donor' => $donor_management, 'provinces' => $provinces]);
    }

    /**
     * Update the specified donor.
     */
    public function update(UpdateDonorRequest $request, Donor $donor_management): RedirectResponse
    {
        DB::transaction(function () use ($request, $donor_management) {
            // Update user if needed
            $userData = [];
            if ($request->has('full_name')) {
                $userData['full_name'] = $request->full_name;
            }
            if ($request->has('email')) {
                $userData['email'] = $request->email;
            }
            if ($request->has('password') && ! empty($request->password)) {
                $userData['password'] = Hash::make($request->password);
            }

            if (! empty($userData)) {
                $donor_management->user->update($userData);
            }

            // Update donor
            $donorData = $request->only([
                'mobile_number',
                'national_code',
                'age',
                'gender',
                'province_id',
                'city_id',
                'address',
                'blood_type',
                'rh_factor',
                'last_donation_date',
            ]);

            if ($request->has('health_status')) {
                $donorData['health_status'] = $request->boolean('health_status');
            }

            if ($request->has('ability_to_donate')) {
                $donorData['ability_to_donate'] = $request->boolean('ability_to_donate');
            }

            $donor_management->update($donorData);
        });

        return redirect()->route('admin.donor-management.index')
            ->with('success', __('admin.Donor updated successfully.'));
    }

    /**
     * Remove the specified donor.
     */
    public function destroy(Donor $donor_management): RedirectResponse
    {
        $donor_management->delete();

        return redirect()->route('admin.donor-management.index')
            ->with('success', __('admin.Donor deleted successfully.'));
    }

    /**
     * Toggle health status of the donor.
     */
    public function toggleHealthStatus(Donor $donor): RedirectResponse
    {
        $donor->health_status = ! $donor->health_status;
        $donor->save();

        return redirect()->route('admin.donor-management.index')
            ->with('success', __('admin.Health status updated successfully.'));
    }

    /**
     * Toggle donation ability of the donor.
     */
    public function toggleDonationAbility(Donor $donor): RedirectResponse
    {
        $donor->ability_to_donate = ! $donor->ability_to_donate;
        $donor->save();

        return redirect()->route('admin.donor-management.index')
            ->with('success', __('admin.Donation ability updated successfully.'));
    }
}
