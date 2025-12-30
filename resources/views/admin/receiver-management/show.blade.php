@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-admin-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.Receiver Details') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('admin.View receiver information and details') }}</p>
                </div>
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} gap-2">
                    <a href="{{ route('admin.receiver-management.edit', $receiver) }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 {{ $isRtl ? 'ml-2' : 'mr-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.Edit') }}
                    </a>
                    <form method="POST" 
                          action="{{ route('admin.receiver-management.destroy', $receiver) }}" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.Are you sure you want to delete this receiver?') }}')">
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
                    <a href="{{ route('admin.receiver-management.index') }}" 
                       class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                        {{ __('admin.Back to List') }}
                    </a>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.User Information') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Full Name -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Full Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->user->full_name }}</dd>
                        </div>

                        <!-- Email -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->user->email }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Receiver Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Receiver Profile') }}</h2>
                </div>
                
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Mobile Number -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Mobile Number') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->mobile_number }}</dd>
                        </div>

                        <!-- National Code -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.National Code') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->national_code }}</dd>
                        </div>

                        <!-- Age -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Age') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->age }}</dd>
                        </div>

                        <!-- Gender -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Gender') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                @if($receiver->gender == 'male')
                                    {{ __('admin.Male') }}
                                @elseif($receiver->gender == 'female')
                                    {{ __('admin.Female') }}
                                @else
                                    {{ __('admin.Other') }}
                                @endif
                            </dd>
                        </div>

                        <!-- Blood Type -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Blood Type') }}</dt>
                            <dd class="mt-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    {{ $receiver->blood_type }}{{ $receiver->rh_factor == 'positive' ? '+' : '-' }}
                                </span>
                            </dd>
                        </div>

                        <!-- RH Factor -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.RH Factor') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                {{ $receiver->rh_factor == 'positive' ? __('admin.Positive') : __('admin.Negative') }}
                            </dd>
                        </div>

                        <!-- Province -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Province') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->province->name ?? '' }}</dd>
                        </div>

                        <!-- City -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.City') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->city->name ?? '' }}</dd>
                        </div>

                        <!-- Address -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Address') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->address }}</dd>
                        </div>

                        <!-- Created At -->
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Created At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ $receiver->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Blood Requests -->
            @if($receiver->bloodRequests->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">{{ __('admin.Recent Blood Requests') }}</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Request ID') }}</th>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Date') }}</th>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Patient') }}</th>
                                        <th class="px-4 py-3 {{ $isRtl ? 'text-right' : 'text-left' }} text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('admin.Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($receiver->bloodRequests as $request)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                #{{ $request->id }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ $request->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                {{ $request->patient_name }}
                                            </td>
                                            <td class="px-4 py-3 text-sm {{ $isRtl ? 'text-right' : 'text-left' }}">
                                                @php
                                                    $statusLabels = [
                                                        0 => __('admin.Pending'),
                                                        1 => __('admin.Approved'),
                                                        2 => __('admin.Rejected'),
                                                        3 => __('admin.Completed'),
                                                    ];
                                                    $statusColors = [
                                                        0 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                        1 => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                        2 => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                        3 => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                                    {{ $statusLabels[$request->status] ?? __('admin.Unknown') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

