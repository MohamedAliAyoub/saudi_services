<div class="filament-info-widget p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="flex items-center gap-3">
        <img src="{{ auth()->user()->image_url }}" alt="User Avatar" class="h-10 w-10 rounded-full">
        <h1 class="text-gray-900 dark:text-gray-100">{{ __('message.welcome') }}</h1>
    </div>
    <p class="text-gray-700 dark:text-gray-300">{{ __('message.welcome') }} {{ __('message.APP_NAME') }}</p>
</div>
