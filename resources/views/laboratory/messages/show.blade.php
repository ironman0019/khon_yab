@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
    $currentUser = auth()->user();
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('laboratory.Messages') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-4">
                    <a href="{{ route('laboratory.messages.index') }}" 
                       class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->full_name }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Messages Thread -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === $currentUser->id ? ($isRtl ? 'justify-start' : 'justify-end') : ($isRtl ? 'justify-end' : 'justify-start') }}">
                            <div class="max-w-[70%] {{ $message->sender_id === $currentUser->id ? ($isRtl ? 'text-right' : 'text-left') : ($isRtl ? 'text-left' : 'text-right') }}">
                                <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} items-center gap-2 mb-1">
                                    <span class="text-xs font-medium {{ $message->sender_id === $currentUser->id ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400' }}">
                                        {{ $message->sender_id === $currentUser->id ? __('laboratory.You') : $message->sender->full_name }}
                                    </span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                </div>
                                <div class="rounded-lg p-3 {{ $message->sender_id === $currentUser->id ? 'bg-red-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' }}">
                                    @if($message->subject && $loop->first)
                                        <div class="font-semibold mb-2">{{ $message->subject }}</div>
                                    @endif
                                    <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('laboratory.No messages yet') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Reply Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('laboratory.messages.store') }}">
                    @csrf
                    <input type="hidden" name="recipient_user_type" value="{{ $user->user_type }}">
                    <input type="hidden" name="recipient_email" value="{{ $user->email }}">
                    
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('laboratory.Message') }}
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="4" 
                            required
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('laboratory.Type your message...') }}"
                        ></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-end gap-2">
                        <a href="{{ route('laboratory.messages.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                            {{ __('laboratory.Cancel') }}
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            {{ __('laboratory.Send Message') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

