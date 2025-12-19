<?php

namespace App\Http\Controllers\Admin\HospitalUserManagement;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HospitalUserManagement\StoreHospitalUserRequest;
use App\Http\Requests\Admin\HospitalUserManagement\UpdateHospitalUserRequest;
use App\Models\HospitalUser;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HospitalUserController extends Controller
{
    /**
     * Display a listing of hospital users.
     */
    public function index(Request $request): View
    {
        $query = HospitalUser::with(['user', 'province', 'city']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('hospital_name', 'like', "%{$search}%")
                    ->orWhere('hospital_code', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            });
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->get('province_id'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('status', $status);
        }

        $hospitalUsers = $query->latest()->paginate(15)->withQueryString();
        $provinces = Province::all();

        return view('admin.hospital-user-management.index', compact('hospitalUsers', 'provinces'));
    }

    /**
     * Show the form for creating a new hospital user.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('admin.hospital-user-management.create', compact('provinces'));
    }

    /**
     * Store a newly created hospital user.
     */
    public function store(StoreHospitalUserRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Create user first
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::HospitalUser->value,
            ]);

            // Create hospital user profile
            HospitalUser::create([
                'user_id' => $user->id,
                'hospital_name' => $request->hospital_name,
                'hospital_code' => $request->hospital_code,
                'mobile_number' => $request->mobile_number,
                'phone_number' => $request->phone_number,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'license_number' => $request->license_number,
                'contact_person_name' => $request->contact_person_name,
                'status' => $request->status ?? 0,
            ]);
        });

        return redirect()->route('admin.hospital-user-management.index')
            ->with('success', __('admin.Hospital user created successfully.'));
    }

    /**
     * Display the specified hospital user.
     */
    public function show(HospitalUser $hospital_user_management): View
    {
        $hospital_user_management->load([
            'user',
            'province',
            'city',
            'bloodRequests' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return view('admin.hospital-user-management.show', ['hospitalUser' => $hospital_user_management]);
    }

    /**
     * Show the form for editing the specified hospital user.
     */
    public function edit(HospitalUser $hospital_user_management): View
    {
        $hospital_user_management->load(['user', 'province', 'city']);
        $provinces = Province::all();

        return view('admin.hospital-user-management.edit', ['hospitalUser' => $hospital_user_management, 'provinces' => $provinces]);
    }

    /**
     * Update the specified hospital user.
     */
    public function update(UpdateHospitalUserRequest $request, HospitalUser $hospital_user_management): RedirectResponse
    {
        DB::transaction(function () use ($request, $hospital_user_management) {
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
                $hospital_user_management->user->update($userData);
            }

            // Update hospital user
            $hospitalUserData = $request->only([
                'hospital_name',
                'hospital_code',
                'mobile_number',
                'phone_number',
                'province_id',
                'city_id',
                'address',
                'license_number',
                'contact_person_name',
            ]);

            if ($request->has('status')) {
                $hospitalUserData['status'] = $request->status;
            }

            $hospital_user_management->update($hospitalUserData);
        });

        return redirect()->route('admin.hospital-user-management.index')
            ->with('success', __('admin.Hospital user updated successfully.'));
    }

    /**
     * Remove the specified hospital user.
     */
    public function destroy(HospitalUser $hospital_user_management): RedirectResponse
    {
        $hospital_user_management->delete();

        return redirect()->route('admin.hospital-user-management.index')
            ->with('success', __('admin.Hospital user deleted successfully.'));
    }

    /**
     * Toggle status of the hospital user.
     */
    public function toggleStatus(HospitalUser $hospital_user): RedirectResponse
    {
        // Toggle between active (1) and inactive (2)
        $hospital_user->status = $hospital_user->status === 1 ? 2 : 1;
        $hospital_user->save();

        return redirect()->route('admin.hospital-user-management.index')
            ->with('success', __('admin.Status updated successfully.'));
    }
}

