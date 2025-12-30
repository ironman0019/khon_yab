<?php

namespace App\Http\Controllers\Admin\ReceiverManagement;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReceiverManagement\StoreReceiverRequest;
use App\Http\Requests\Admin\ReceiverManagement\UpdateReceiverRequest;
use App\Models\Province;
use App\Models\Receiver;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ReceiverController extends Controller
{
    /**
     * Display a listing of receivers.
     */
    public function index(Request $request): View
    {
        $query = Receiver::with(['user', 'province', 'city']);

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

        $receivers = $query->latest()->paginate(15)->withQueryString();
        $provinces = Province::all();

        return view('admin.receiver-management.index', compact('receivers', 'provinces'));
    }

    /**
     * Show the form for creating a new receiver.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('admin.receiver-management.create', compact('provinces'));
    }

    /**
     * Store a newly created receiver.
     */
    public function store(StoreReceiverRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            // Create user first
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type' => UserType::Receiver->value,
            ]);

            // Create receiver profile
            Receiver::create([
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
            ]);
        });

        return redirect()->route('admin.receiver-management.index')
            ->with('success', __('admin.Receiver created successfully.'));
    }

    /**
     * Display the specified receiver.
     */
    public function show(Receiver $receiver_management): View
    {
        $receiver_management->load([
            'user',
            'province',
            'city',
            'bloodRequests' => function ($query) {
                $query->latest()->limit(10);
            },
        ]);

        return view('admin.receiver-management.show', ['receiver' => $receiver_management]);
    }

    /**
     * Show the form for editing the specified receiver.
     */
    public function edit(Receiver $receiver_management): View
    {
        $receiver_management->load(['user', 'province', 'city']);
        $provinces = Province::all();

        return view('admin.receiver-management.edit', ['receiver' => $receiver_management, 'provinces' => $provinces]);
    }

    /**
     * Update the specified receiver.
     */
    public function update(UpdateReceiverRequest $request, Receiver $receiver_management): RedirectResponse
    {
        DB::transaction(function () use ($request, $receiver_management) {
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
                $receiver_management->user->update($userData);
            }

            // Update receiver
            $receiverData = $request->only([
                'mobile_number',
                'national_code',
                'age',
                'gender',
                'province_id',
                'city_id',
                'address',
                'blood_type',
                'rh_factor',
            ]);

            $receiver_management->update($receiverData);
        });

        return redirect()->route('admin.receiver-management.index')
            ->with('success', __('admin.Receiver updated successfully.'));
    }

    /**
     * Remove the specified receiver.
     */
    public function destroy(Receiver $receiver_management): RedirectResponse
    {
        $receiver_management->delete();

        return redirect()->route('admin.receiver-management.index')
            ->with('success', __('admin.Receiver deleted successfully.'));
    }
}
