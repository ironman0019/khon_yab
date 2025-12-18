@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.User Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View user information and details') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <a href="{{ route('admin.user-management.edit', $user) }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('admin.user-management.destroy', $user) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this user?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            {{ __('admin.Delete') }}
                        </button>
                    </form>
                    <a href="{{ route('admin.user-management.index') }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.User Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Full Name -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Full Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $user->full_name }}</dd>
                        </div>

                        <!-- Email -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $user->email }}</dd>
                        </div>

                        <!-- User Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.User Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @php
                                    $userTypeLabels = [
                                        0 => __('admin.User'),
                                        1 => __('admin.Donor'),
                                        2 => __('admin.Hospital User'),
                                    ];
                                    $userTypeColors = [
                                        0 => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        2 => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $userTypeColors[$user->user_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                    {{ $userTypeLabels[$user->user_type] ?? __('admin.Unknown') }}
                                </span>
                            </dd>
                        </div>

                        <!-- Admin Status -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Admin Status') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @if($user->is_admin)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        {{ __('admin.Yes') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                        {{ __('admin.No') }}
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <!-- Email Verified -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Email Verified') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        {{ __('admin.Yes') }} - {{ $user->email_verified_at->format('Y-m-d H:i') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        {{ __('admin.No') }}
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <!-- Created At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <!-- Updated At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Updated At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Related Information -->
            @if($user->donor)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Donor Profile') }}</h2>
                    </div>
                    <div class="px-6 py-4 {{ $isRtl ? 'text-right' : 'text-left' }}">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('admin.This user has a donor profile.') }}
                            <a href="{{ route('admin.donor-management.show', $user->donor) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                {{ __('admin.View donor profile') }}
                            </a>
                        </p>
                    </div>
                </div>
            @endif

            @if($user->bloodRequests->count() > 0)
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Requests') }}</h2>
                    </div>
                    <div class="px-6 py-4 {{ $isRtl ? 'text-right' : 'text-left' }}">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('admin.This user has made :count blood request(s).', ['count' => $user->bloodRequests->count()]) }}
                            <a href="{{ route('admin.blood-request-management.index', ['requested_by' => $user->id]) }}" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                {{ __('admin.View requests') }}
                            </a>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
