@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.City Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View city information and details') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <a href="{{ route('admin.province-management.cities.edit', [$province, $city]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('admin.province-management.cities.destroy', [$province, $city]) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this city?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin.Delete') }}
                        </button>
                    </form>
                    <a href="{{ route('admin.province-management.cities.index', $province) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- City Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.City Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- City Name -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.City Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg">{{ $city->name }}</dd>
                        </div>

                        <!-- Province -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Province') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                <a href="{{ route('admin.province-management.show', $city->province) }}" 
                                   class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                    {{ $city->province->name ?? '' }}
                                </a>
                            </dd>
                        </div>

                        <!-- Created At -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $city->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $city->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
