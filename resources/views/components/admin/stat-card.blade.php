@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'red',
    'trend' => null,
])

@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp

@php
    $colorClasses = [
        'red' => 'bg-red-500 dark:bg-red-600',
        'blue' => 'bg-blue-500 dark:bg-blue-600',
        'green' => 'bg-green-500 dark:bg-green-600',
        'yellow' => 'bg-yellow-500 dark:bg-yellow-600',
        'purple' => 'bg-purple-500 dark:bg-purple-600',
    ];
    
    $iconBgClasses = [
        'red' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400',
        'blue' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
        'green' => 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400',
        'yellow' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400',
        'purple' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
    ];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
        <div class="flex-1 {{ $isRtl ? 'text-right' : 'text-left' }}">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            @if($trend)
                <p class="mt-2 text-sm {{ $trend['direction'] === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    <span>{{ $trend['value'] }}</span>
                    <span>{{ $trend['label'] }}</span>
                </p>
            @endif
        </div>
        @if(isset($icon))
            <div class="flex-shrink-0 {{ $isRtl ? 'ml-4' : 'mr-4' }}">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg {{ $iconBgClasses[$color] }}">
                    {{ $icon }}
                </div>
            </div>
        @endif
    </div>
</div>
