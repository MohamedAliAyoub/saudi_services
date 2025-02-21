<div class="filament-info-widget p-4 bg-white shadow rounded-lg">
    <div class="flex items-center gap-3">
        <img src="{{ auth()->user()->image_url}}" alt="User Avatar" class="h-10 w-10 rounded-full">
        <h1>{{ __('message.welcome') }}</h1>
    </div>
    <p>{{ __('message.welcome') }} {{ __('message.APP_NAME') }}</p>
</div>
