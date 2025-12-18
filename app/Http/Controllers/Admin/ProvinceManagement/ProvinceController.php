<?php

namespace App\Http\Controllers\Admin\ProvinceManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProvinceManagement\StoreProvinceRequest;
use App\Http\Requests\Admin\ProvinceManagement\UpdateProvinceRequest;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProvinceController extends Controller
{
    /**
     * Display a listing of provinces.
     */
    public function index(Request $request): View
    {
        $query = Province::withCount('cities');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $provinces = $query->latest()->paginate(15);

        return view('admin.province-management.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new province.
     */
    public function create(): View
    {
        return view('admin.province-management.create');
    }

    /**
     * Store a newly created province.
     */
    public function store(StoreProvinceRequest $request): RedirectResponse
    {
        Province::create($request->validated());

        return redirect()->route('admin.province-management.index')
            ->with('success', __('admin.Province created successfully.'));
    }

    /**
     * Display the specified province.
     */
    public function show(Province $province_management): View
    {
        $province_management->load(['cities' => function ($query) {
            $query->latest();
        }]);

        return view('admin.province-management.show', ['province' => $province_management]);
    }

    /**
     * Show the form for editing the specified province.
     */
    public function edit(Province $province_management): View
    {
        return view('admin.province-management.edit', ['province' => $province_management]);
    }

    /**
     * Update the specified province.
     */
    public function update(UpdateProvinceRequest $request, Province $province_management): RedirectResponse
    {
        $province_management->update($request->validated());

        return redirect()->route('admin.province-management.index')
            ->with('success', __('admin.Province updated successfully.'));
    }

    /**
     * Remove the specified province.
     */
    public function destroy(Province $province_management): RedirectResponse
    {
        $province_management->delete();

        return redirect()->route('admin.province-management.index')
            ->with('success', __('admin.Province deleted successfully.'));
    }
}
