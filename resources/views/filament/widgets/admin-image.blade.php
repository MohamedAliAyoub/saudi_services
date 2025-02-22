<div class="filament-info-widget p-4 bg-white dark:bg-gray-800 shadow rounded-lg">
    <div class="flex items-center gap-3">
        <img src="{{ env('APP_IMAGE') }}" alt="{{ __('message.company_name') }}" class="h-10 w-10 rounded-full">
        <h1 class="text-gray-900 dark:text-gray-100">{{ __('message.company_name') }}</h1>
    </div>
    <p class="text-gray-700 dark:text-gray-300">{{ __('message.company_welcome') }}</p>
</div>
