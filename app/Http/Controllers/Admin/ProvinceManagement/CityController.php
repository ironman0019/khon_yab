<?php

namespace App\Http\Controllers\Admin\ProvinceManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProvinceManagement\StoreCityRequest;
use App\Http\Requests\Admin\ProvinceManagement\UpdateCityRequest;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    /**
     * Display a listing of cities.
     */
    public function index(Request $request, Province $province_management): View
    {
        $query = City::with('province')->where('province_id', $province_management->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $cities = $query->latest()->paginate(15)->withQueryString();
        $provinces = Province::all();

        return view('admin.province-management.cities.index', [
            'cities' => $cities,
            'provinces' => $provinces,
            'province' => $province_management,
        ]);
    }

    /**
     * Show the form for creating a new city.
     */
    public function create(Province $province_management): View
    {
        $provinces = Province::all();

        return view('admin.province-management.cities.create', [
            'provinces' => $provinces,
            'province' => $province_management,
        ]);
    }

    /**
     * Store a newly created city.
     */
    public function store(StoreCityRequest $request, Province $province_management): RedirectResponse
    {
        $data = $request->validated();
        $data['province_id'] = $province_management->id;
        City::create($data);

        return redirect()->route('admin.province-management.cities.index', $province_management)
            ->with('success', __('admin.City created successfully.'));
    }

    /**
     * Display the specified city.
     */
    public function show(Province $province_management, City $city): View
    {
        $city->load('province');

        return view('admin.province-management.cities.show', [
            'city' => $city,
            'province' => $province_management,
        ]);
    }

    /**
     * Show the form for editing the specified city.
     */
    public function edit(Province $province_management, City $city): View
    {
        $city->load('province');
        $provinces = Province::all();

        return view('admin.province-management.cities.edit', [
            'city' => $city,
            'provinces' => $provinces,
            'province' => $province_management,
        ]);
    }

    /**
     * Update the specified city.
     */
    public function update(UpdateCityRequest $request, Province $province_management, City $city): RedirectResponse
    {
        $city->update($request->validated());

        return redirect()->route('admin.province-management.cities.index', $province_management)
            ->with('success', __('admin.City updated successfully.'));
    }

    /**
     * Remove the specified city.
     */
    public function destroy(Province $province_management, City $city): RedirectResponse
    {
        $city->delete();

        return redirect()->route('admin.province-management.cities.index', $province_management)
            ->with('success', __('admin.City deleted successfully.'));
    }
}
