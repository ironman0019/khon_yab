<?php

namespace App\Http\Controllers\Admin\LaboratoryManagement;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaboratoryManagement\StoreLaboratoryRequest;
use App\Http\Requests\Admin\LaboratoryManagement\UpdateLaboratoryRequest;
use App\Models\Laboratory;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of laboratories.
     */
    public function index(Request $request): View
    {
        $query = Laboratory::with(['user', 'province', 'city']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('laboratory_name', 'like', "%{$search}%")
                    ->orWhere('laboratory_code', 'like', "%{$search}%")
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

        $laboratories = $query->latest()->paginate(15)->withQueryString();
        $provinces = Province::all();

        return view('admin.laboratory-management.index', compact('laboratories', 'provinces'));
    }

    /**
     * Show the form for creating a new laboratory.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('admin.laboratory-management.create', compact('provinces'));
    }

    /**
     * Store a newly created laboratory.
     */
    public function store(StoreLaboratoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Create user first
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::Laboratory->value,
            ]);

            // Create laboratory profile
            Laboratory::create([
                'user_id' => $user->id,
                'laboratory_name' => $request->laboratory_name,
                'laboratory_code' => $request->laboratory_code,
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

        return redirect()->route('admin.laboratory-management.index')
            ->with('success', __('admin.Laboratory created successfully.'));
    }

    /**
     * Display the specified laboratory.
     */
    public function show(Laboratory $laboratory_management): View
    {
        $laboratory_management->load([
            'user',
            'province',
            'city',
            'bloodRequests' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return view('admin.laboratory-management.show', ['laboratory' => $laboratory_management]);
    }

    /**
     * Show the form for editing the specified laboratory.
     */
    public function edit(Laboratory $laboratory_management): View
    {
        $laboratory_management->load(['user', 'province', 'city']);
        $provinces = Province::all();

        return view('admin.laboratory-management.edit', ['laboratory' => $laboratory_management, 'provinces' => $provinces]);
    }

    /**
     * Update the specified laboratory.
     */
    public function update(UpdateLaboratoryRequest $request, Laboratory $laboratory_management): RedirectResponse
    {
        DB::transaction(function () use ($request, $laboratory_management) {
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
                $laboratory_management->user->update($userData);
            }

            // Update laboratory
            $laboratoryData = $request->only([
                'laboratory_name',
                'laboratory_code',
                'mobile_number',
                'phone_number',
                'province_id',
                'city_id',
                'address',
                'license_number',
                'contact_person_name',
            ]);

            if ($request->has('status')) {
                $laboratoryData['status'] = $request->status;
            }

            $laboratory_management->update($laboratoryData);
        });

        return redirect()->route('admin.laboratory-management.index')
            ->with('success', __('admin.Laboratory updated successfully.'));
    }

    /**
     * Remove the specified laboratory.
     */
    public function destroy(Laboratory $laboratory_management): RedirectResponse
    {
        $laboratory_management->delete();

        return redirect()->route('admin.laboratory-management.index')
            ->with('success', __('admin.Laboratory deleted successfully.'));
    }

    /**
     * Toggle status of the laboratory.
     */
    public function toggleStatus(Laboratory $laboratory): RedirectResponse
    {
        // Toggle between active (1) and inactive (2)
        $laboratory->status = $laboratory->status === 1 ? 2 : 1;
        $laboratory->save();

        return redirect()->route('admin.laboratory-management.index')
            ->with('success', __('admin.Status updated successfully.'));
    }
}
