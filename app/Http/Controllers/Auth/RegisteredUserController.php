<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegisteredUserRequest;
use App\Models\Donor;
use App\Models\HospitalUser;
use App\Models\Province;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $provinces = Province::all();

        return view('auth.register', compact('provinces'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRegisteredUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            // Create user
            $user = User::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'],
                'is_admin' => false,
            ]);

            // If donor, create donor profile
            if ($validated['user_type'] == UserType::Donor->value) {
                Donor::create([
                    'user_id' => $user->id,
                    'mobile_number' => $validated['mobile_number'],
                    'national_code' => $validated['national_code'],
                    'age' => $validated['age'],
                    'gender' => $validated['gender'],
                    'province_id' => $validated['province_id'],
                    'city_id' => $validated['city_id'],
                    'address' => $validated['address'],
                    'blood_type' => $validated['blood_type'],
                    'rh_factor' => $validated['rh_factor'],
                    'health_status' => false,
                    'ability_to_donate' => false,
                ]);
            }

            // If hospital_user, create hospital_user profile
            if ($validated['user_type'] == UserType::HospitalUser->value) {
                HospitalUser::create([
                    'user_id' => $user->id,
                    'hospital_name' => $validated['hospital_name'],
                    'hospital_code' => $validated['hospital_code'],
                    'mobile_number' => $validated['hospital_mobile_number'],
                    'phone_number' => $validated['hospital_phone_number'] ?? null,
                    'province_id' => $validated['hospital_province_id'],
                    'city_id' => $validated['hospital_city_id'],
                    'address' => $validated['hospital_address'],
                    'license_number' => $validated['license_number'] ?? null,
                    'contact_person_name' => $validated['contact_person_name'],
                    'status' => 0, // pending
                ]);
            }

            event(new Registered($user));

            Auth::login($user);

            // Redirect based on user type
            return $this->redirectBasedOnUserType($user);
        });
    }

    /**
     * Redirect user based on their type.
     */
    protected function redirectBasedOnUserType(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard.index');
        }

        return match ($user->user_type) {
            UserType::Donor->value => redirect()->route('donor.dashboard.index'),
            UserType::HospitalUser->value => redirect()->route('hospital.dashboard.index'),
            default => redirect()->route('dashboard'),
        };
    }
}
