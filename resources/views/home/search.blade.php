<x-public-layout>
    @php
        $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    @endphp

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ __('home.Blood Request Search') }}
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">
                    {{ __('home.Find blood requests in your area') }}
                </p>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
                <form method="GET" action="{{ route('home.search') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Blood Type Filter -->
                        <div>
                            <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('home.Blood Type') }}
                            </label>
                            <select 
                                id="blood_type" 
                                name="blood_type"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-red-500 focus:ring-red-500"
                            >
                                <option value="">{{ __('home.All Types') }}</option>
                                <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                        </div>

                        <!-- Province Filter -->
                        <div>
                            <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('home.Province') }}
                            </label>
                            <select 
                                id="province_id" 
                                name="province_id"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-red-500 focus:ring-red-500"
                            >
                                <option value="">{{ __('home.Select Province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City Filter -->
                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('home.City') }}
                            </label>
                            <select 
                                id="city_id" 
                                name="city_id"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-red-500 focus:ring-red-500"
                                {{ !request('province_id') ? 'disabled' : '' }}
                            >
                                <option value="">{{ __('home.Select City') }}</option>
                                @if(request('province_id'))
                                    @php
                                        $selectedProvince = $provinces->firstWhere('id', request('province_id'));
                                    @endphp
                                    @if($selectedProvince)
                                        @foreach($selectedProvince->cities as $city)
                                            <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end gap-2">
                            <button 
                                type="submit"
                                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                            >
                                {{ __('home.Search') }}
                            </button>
                            <a 
                                href="{{ route('home.search') }}"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors"
                            >
                                {{ __('home.Clear Filters') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Section -->
            @if($bloodRequests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bloodRequests as $request)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $request->patient_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('home.Patient Age') }}: {{ $request->patient_age }}
                                    </p>
                                </div>
                                <div class="px-3 py-1 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                    <span class="text-red-600 dark:text-red-400 font-semibold">
                                        {{ $request->blood_type }}{{ $request->rh_factor }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span><strong>{{ __('home.Bags Needed') }}:</strong> {{ $request->number_of_bags }}</span>
                                </div>

                                <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span><strong>{{ __('home.Medical Center') }}:</strong> {{ $request->medical_center }}</span>
                                </div>

                                <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span><strong>{{ __('home.Location') }}:</strong> {{ $request->province->name }}, {{ $request->city->name }}</span>
                                </div>

                                <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span><strong>{{ __('home.Contact Number') }}:</strong> {{ $request->contact_number }}</span>
                                </div>
                            </div>

                            @if($request->request_reason)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <strong>{{ __('home.Request Reason') }}:</strong> {{ Str::limit($request->request_reason, 100) }}
                                </p>
                            </div>
                            @endif

                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('home.Request Date') }}: {{ $request->created_at->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bloodRequests->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        {{ __('home.No blood requests found') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('home.Try adjusting your filters to see more results.') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const selectCityText = '{{ __('home.Select City') }}';
            const selectedCityId = '{{ request('city_id') }}';

            function loadCities(provinceId) {
                // Clear city options
                citySelect.innerHTML = `<option value="">${selectCityText}</option>`;
                
                if (provinceId) {
                    // Enable city select
                    citySelect.disabled = false;
                    
                    // Fetch cities for selected province
                    fetch(`{{ route('api.cities') }}?province_id=${provinceId}`)
                        .then(response => response.json())
                        .then(cities => {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                if (selectedCityId && city.id == selectedCityId) {
                                    option.selected = true;
                                }
                                citySelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                        });
                } else {
                    // Disable city select if no province selected
                    citySelect.disabled = true;
                }
            }

            // Initialize cities on page load if province is already selected
            const selectedProvinceId = provinceSelect.value;
            if (selectedProvinceId) {
                loadCities(selectedProvinceId);
            }

            // Handle province change
            provinceSelect.addEventListener('change', function() {
                loadCities(this.value);
            });
        });
    </script>
    @endpush
</x-public-layout>

