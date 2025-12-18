<?php

namespace App\Http\Controllers\Admin\LanguageManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageManagement\StoreLanguageRequest;
use App\Http\Requests\Admin\LanguageManagement\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages.
     */
    public function index(Request $request): View
    {
        $query = Language::query();

        // Filter by active status
        if ($request->filled('is_active')) {
            $isActive = $request->get('is_active');
            if ($isActive === '1' || $isActive === 1 || $isActive === true) {
                $query->where('is_active', true);
            } elseif ($isActive === '0' || $isActive === 0 || $isActive === false) {
                $query->where('is_active', false);
            }
        }

        // Search functionality - only apply if search term is not empty
        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('native_name', 'like', "%{$search}%");
                });
            }
        }

        $languages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.language-management.index', compact('languages'));
    }

    /**
     * Show the form for creating a new language.
     */
    public function create(): View
    {
        return view('admin.language-management.create');
    }

    /**
     * Store a newly created language.
     */
    public function store(StoreLanguageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_default'] = $request->boolean('is_default', false);

        // If setting as default, unset other defaults
        if ($data['is_default']) {
            DB::table('languages')->update(['is_default' => false]);
        }

        Language::create($data);

        return redirect()->route('admin.language-management.index')
            ->with('success', __('admin.Language created successfully.'));
    }

    /**
     * Display the specified language.
     */
    public function show(Language $language): View
    {
        $language->load('translations');

        return view('admin.language-management.show', compact('language'));
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language): View
    {
        return view('admin.language-management.edit', compact('language'));
    }

    /**
     * Update the specified language.
     */
    public function update(UpdateLanguageRequest $request, Language $language): RedirectResponse
    {
        $data = $request->validated();

        // If setting as default, unset other defaults
        if ($request->has('is_default') && $request->boolean('is_default')) {
            DB::table('languages')
                ->where('id', '!=', $language->id)
                ->update(['is_default' => false]);
        }

        $language->update($data);

        return redirect()->route('admin.language-management.index')
            ->with('success', __('admin.Language updated successfully.'));
    }

    /**
     * Remove the specified language.
     */
    public function destroy(Language $language): RedirectResponse
    {
        // Prevent deletion of default language
        if ($language->is_default) {
            return redirect()->route('admin.language-management.index')
                ->with('error', __('admin.Cannot delete the default language.'));
        }

        $language->delete();

        return redirect()->route('admin.language-management.index')
            ->with('success', __('admin.Language deleted successfully.'));
    }

    /**
     * Toggle active status of the language.
     */
    public function toggleActive(Language $language): RedirectResponse
    {
        // Prevent deactivating default language
        if ($language->is_default && $language->is_active) {
            return redirect()->route('admin.language-management.index')
                ->with('error', __('admin.Cannot deactivate the default language.'));
        }

        $language->is_active = ! $language->is_active;
        $language->save();

        return redirect()->route('admin.language-management.index')
            ->with('success', __('admin.Language status updated successfully.'));
    }

    /**
     * Set language as default.
     */
    public function setDefault(Language $language): RedirectResponse
    {
        DB::transaction(function () use ($language) {
            // Unset all defaults
            DB::table('languages')->update(['is_default' => false]);

            // Set this as default and active
            $language->update([
                'is_default' => true,
                'is_active' => true,
            ]);
        });

        return redirect()->route('admin.language-management.index')
            ->with('success', __('admin.Default language updated successfully.'));
    }
}
