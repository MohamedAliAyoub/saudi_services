{{-- resources/views/vendor/filament/components/user-menu.blade.php --}}
@props([
    'user' => auth()->user(),
])

<div class="fi-user-menu">
    <x-custom-avatar :src="$user->image_url" size="md" />
    <span>{{ $user->name ?? 'Guest' }}</span>
</div>
