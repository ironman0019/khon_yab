@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('laboratory.New Message') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6 flex {{ $isRtl ? 'flex-row-reverse' : '' }} justify-between items-center">
                <div class="{{ $isRtl ? 'text-right' : 'text-left' }}">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('laboratory.New Message') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('laboratory.Send a message to a user') }}</p>
                </div>
                <a href="{{ route('laboratory.messages.index') }}" 
                   class="inline-flex items-center {{ $isRtl ? 'flex-row-reverse' : '' }} px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    {{ __('laboratory.Back') }}
                </a>
            </div>

            <!-- Message Form -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('laboratory.messages.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="recipient_user_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('laboratory.Recipient User Type') }}
                        </label>
                        <select 
                            id="recipient_user_type" 
                            name="recipient_user_type" 
                            required
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">{{ __('laboratory.Select user type') }}</option>
                            <option value="0" {{ old('recipient_user_type') == '0' ? 'selected' : '' }}>{{ __('laboratory.Receiver') }}</option>
                            <option value="1" {{ old('recipient_user_type') == '1' ? 'selected' : '' }}>{{ __('laboratory.Donor') }}</option>
                            <option value="2" {{ old('recipient_user_type') == '2' ? 'selected' : '' }}>{{ __('laboratory.Laboratory') }}</option>
                            <option value="-1" {{ old('recipient_user_type') == '-1' ? 'selected' : '' }}>{{ __('laboratory.Admin') }}</option>
                        </select>
                        @error('recipient_user_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4" x-data="emailAutocomplete()">
                        <label for="recipient_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('laboratory.Recipient Email') }}
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                id="recipient_email" 
                                name="recipient_email" 
                                x-model="email"
                                @input="searchEmails()"
                                @focus="searchEmails()"
                                @blur="setTimeout(() => { showSuggestions = false; }, 200)"
                                required
                                class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                placeholder="{{ __('laboratory.Enter recipient email address') }}"
                                autocomplete="off"
                            >
                            <div x-show="showSuggestions && suggestions.length > 0" 
                                 x-transition
                                 class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-auto">
                                <template x-for="suggestion in suggestions" :key="suggestion.id">
                                    <div @click="selectEmail(suggestion.email)" 
                                         class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="suggestion.email"></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="suggestion.full_name"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        @error('recipient_email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('laboratory.Subject') }} <span class="text-gray-400">({{ __('laboratory.optional') }})</span>
                        </label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            value="{{ old('subject') }}"
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('laboratory.Message subject') }}"
                        >
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('laboratory.Message') }}
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6" 
                            required
                            class="block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('laboratory.Type your message...') }}"
                        >{{ old('message') }}</textarea>
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

    <script>
        function emailAutocomplete() {
            return {
                email: '{{ old('recipient_email') }}',
                suggestions: [],
                showSuggestions: false,
                searchTimeout: null,

                init() {
                    // Listen for user type changes
                    const userTypeSelect = document.getElementById('recipient_user_type');
                    if (userTypeSelect) {
                        userTypeSelect.addEventListener('change', () => {
                            if (this.email && this.email.length >= 2) {
                                this.searchEmails();
                            }
                        });
                    }
                },

                searchEmails() {
                    const query = this.email;
                    const userType = document.getElementById('recipient_user_type')?.value || '';

                    // Clear previous timeout
                    if (this.searchTimeout) {
                        clearTimeout(this.searchTimeout);
                    }

                    // If query is too short, clear suggestions
                    if (query.length < 2) {
                        this.suggestions = [];
                        this.showSuggestions = false;
                        return;
                    }

                    // Debounce the search
                    this.searchTimeout = setTimeout(() => {
                        fetch(`{{ route('api.users.search') }}?q=${encodeURIComponent(query)}&user_type=${encodeURIComponent(userType)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.suggestions = data;
                            this.showSuggestions = data.length > 0;
                        })
                        .catch(error => {
                            console.error('Error searching emails:', error);
                            this.suggestions = [];
                            this.showSuggestions = false;
                        });
                    }, 300);
                },

                selectEmail(email) {
                    this.email = email;
                    this.showSuggestions = false;
                    this.suggestions = [];
                }
            }
        }
    </script>
</x-app-layout>

