@props(['class' => '', 'showText' => true, 'inline' => false])

@if($inline)
    <!-- Inline version for headers (icon only) -->
    <svg viewBox="0 0 200 200" class="{{ $class }}" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        <!-- Blood Drop Shape -->
        <path d="M100 30 C80 30, 60 50, 60 80 C60 110, 80 140, 100 170 C120 140, 140 110, 140 80 C140 50, 120 30, 100 30 Z" 
              class="fill-red-600 dark:fill-red-500" />
        
        <!-- Cross Symbol -->
        <line x1="100" y1="90" x2="100" y2="130" stroke="white" stroke-width="8" stroke-linecap="round" class="dark:stroke-gray-100"/>
        <line x1="85" y1="110" x2="115" y2="110" stroke="white" stroke-width="8" stroke-linecap="round" class="dark:stroke-gray-100"/>
    </svg>
@else
    <!-- Standalone version for login/auth pages -->
    <div class="{{ $class }}">
        <svg viewBox="0 0 200 200" class="w-20 h-20 mx-auto" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Blood Drop Shape -->
            <path d="M100 30 C80 30, 60 50, 60 80 C60 110, 80 140, 100 170 C120 140, 140 110, 140 80 C140 50, 120 30, 100 30 Z" 
                  class="fill-red-600 dark:fill-red-500" />
            
            <!-- Cross Symbol -->
            <line x1="100" y1="90" x2="100" y2="130" stroke="white" stroke-width="8" stroke-linecap="round" class="dark:stroke-gray-100"/>
            <line x1="85" y1="110" x2="115" y2="110" stroke="white" stroke-width="8" stroke-linecap="round" class="dark:stroke-gray-100"/>
        </svg>
        
        @if($showText)
        <!-- Text -->
        <div class="mt-2 text-center">
            <h1 class="text-xl font-bold text-red-600 dark:text-red-400">KhonYab</h1>
            <p class="text-xs text-gray-600 dark:text-gray-400">خون یاب</p>
        </div>
        @endif
    </div>
@endif
