@php
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'color' => 'gray',
    'icon' => null,
    'iconSize' => IconSize::Medium,
    'tag' => 'div',
])

<{{ $tag }}
{{
    $attributes
        ->class([
            'fi-dropdown-header flex w-full gap-2 p-3 text-sm',
            match ($color) {
                'gray' => null,
                default => 'fi-color-custom',
            },
            // @deprecated `fi-dropdown-header-color-*` has been replaced by `fi-color-*` and `fi-color-custom`.
            is_string($color) ? "fi-dropdown-header-color-{$color}" : null,
            is_string($color) ? "fi-color-{$color}" : null,
        ])
}}
>



<img
    src="{{ auth()->user()?->image_url }}"
    class="h-6 w-6 rounded-full object-cover object-center"
/>    <span
        @class([
            'fi-dropdown-header-label flex-1 truncate text-start',
            match ($color) {
                'gray' => 'text-gray-700 dark:text-gray-200',
                default => 'text-custom-600 dark:text-custom-400',
            },
        ])
    @style([
        \Filament\Support\get_color_css_variables(
            $color,
            shades: [400, 600],
            alias: 'dropdown.header.label',
        ) => $color !== 'gray',
    ])
    >
        {{ $slot }}
    </span>
</{{ $tag }}>
