@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Province Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View province information and cities') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <a href="{{ route('admin.province-management.edit', $province) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('admin.province-management.destroy', $province) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this province? This will also delete all cities in this province.') }}')">
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
                    <a href="{{ route('admin.province-management.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- Province Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Name -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Province Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg">{{ $province->name }}</dd>
                        </div>

                        <!-- Cities Count -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Cities Count') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $province->cities->count() }} {{ __('admin.cities') }}</dd>
                        </div>

                        <!-- Created At -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $province->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $province->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Cities List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Cities in this Province') }} ({{ $province->cities->count() }})</h2>
                    <a href="{{ route('admin.province-management.cities.create', $province) }}" 
                       class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 {{ $isRtl ? 'ml-1.5' : 'mr-1.5' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('admin.Add City') }}
                    </a>
                </div>
                @if($province->cities->count() > 0)
                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.City Name') }}</th>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Created At') }}</th>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-left' : 'text-right' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($province->cities as $city)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ $city->name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ $city->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm {{ $isRtl ? 'text-left' : 'text-right' }}">
                                                <div class="flex {{ $isRtl ? 'justify-start' : 'justify-end' }} gap-2">
                                                    <a href="{{ route('admin.province-management.cities.show', [$province, $city]) }}" 
                                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors"
                                                       title="{{ __('admin.View') }}">
                                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('admin.province-management.cities.edit', [$province, $city]) }}" 
                                                       class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors"
                                                       title="{{ __('admin.Edit') }}">
                                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.No cities in this province yet.') }}</p>
                        <a href="{{ route('admin.province-management.cities.create', $province) }}" 
                           class="mt-3 inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('admin.Add First City') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
