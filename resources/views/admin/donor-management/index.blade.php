@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Donor Management') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.Manage all blood donors') }}</p>
                </div>
                <a href="{{ route('admin.donor-management.create') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('admin.Create Donor') }}
                </a>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" action="{{ route('admin.donor-management.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Search') }}</label>
                        <x-text-input 
                            id="search"
                            name="search" 
                            type="text" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ request('search') }}"
                            placeholder="{{ __('admin.Search by name, email, national code...') }}"
                        />
                    </div>

                    <!-- Blood Type Filter -->
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Type') }}</label>
                        <x-select 
                            id="blood_type"
                            name="blood_type" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All Types') }}</option>
                            <option value="A" {{ request('blood_type') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ request('blood_type') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ request('blood_type') == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ request('blood_type') == 'O' ? 'selected' : '' }}>O</option>
                        </x-select>
                    </div>

                    <!-- Province Filter -->
                    <div>
                        <label for="province_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</label>
                        <x-select 
                            id="province_id"
                            name="province_id" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All Provinces') }}</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Health Status Filter -->
                    <div>
                        <label for="health_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Health Status') }}</label>
                        <x-select 
                            id="health_status"
                            name="health_status" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All') }}</option>
                            <option value="1" {{ request('health_status') == '1' ? 'selected' : '' }}>{{ __('admin.Healthy') }}</option>
                            <option value="0" {{ request('health_status') == '0' ? 'selected' : '' }}>{{ __('admin.Not Healthy') }}</option>
                        </x-select>
                    </div>

                    <!-- Ability to Donate Filter -->
                    <div>
                        <label for="ability_to_donate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Can Donate') }}</label>
                        <x-select 
                            id="ability_to_donate"
                            name="ability_to_donate" 
                            class="block w-full border-red-300 focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('admin.All') }}</option>
                            <option value="1" {{ request('ability_to_donate') == '1' ? 'selected' : '' }}>{{ __('admin.Yes') }}</option>
                            <option value="0" {{ request('ability_to_donate') == '0' ? 'selected' : '' }}>{{ __('admin.No') }}</option>
                        </x-select>
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-5 flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-end gap-2">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Filter') }}
                        </button>
                        <a href="{{ route('admin.donor-management.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Reset') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Donors Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Name') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Email') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Blood Type') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Location') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Health Status') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Can Donate') }}
                                </th>
                                <th class="px-6 py-3 {{ $isRtl ? 'text-left' : 'text-right' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('admin.Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($donors as $donor)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $donor->user->full_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $donor->national_code }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $donor->user->email }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $donor->mobile_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            {{ $donor->blood_type }}{{ $donor->rh_factor == 'positive' ? '+' : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $donor->city->name ?? '' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $donor->province->name ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($donor->health_status)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ __('admin.Healthy') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                {{ __('admin.Not Healthy') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($donor->ability_to_donate)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ __('admin.Yes') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                                {{ __('admin.No') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap {{ $isRtl ? 'text-left' : 'text-right' }} text-sm font-medium">
                                        <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                            <a href="{{ route('admin.donor-management.show', $donor) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                               title="{{ __('admin.View') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.donor-management.edit', $donor) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                               title="{{ __('admin.Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" 
                                                  action="{{ route('admin.donor-management.toggle-health-status', $donor) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('admin.Are you sure you want to toggle health status?') }}')">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 transition-colors"
                                                        title="{{ __('admin.Toggle Health Status') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" 
                                                  action="{{ route('admin.donor-management.toggle-donation-ability', $donor) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('admin.Are you sure you want to toggle donation ability?') }}')">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors"
                                                        title="{{ __('admin.Toggle Donation Ability') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form method="POST" 
                                                  action="{{ route('admin.donor-management.destroy', $donor) }}" 
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this donor?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                        title="{{ __('admin.Delete') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.No donors found') }}</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('admin.Get started by creating a new donor.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($donors->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $donors->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
