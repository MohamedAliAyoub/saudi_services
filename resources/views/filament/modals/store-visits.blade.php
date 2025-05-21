<div>
    <h2 class="text-xl font-bold mb-4">{{ __('message.visits_for', ['store' => $store->translated_name]) }}</h2>

    <div class="overflow-x-auto">
        <x-filament::table>
            <x-slot name="header">
                <x-filament::table.header-cell>{{ __('message.date') }}</x-filament::table.header-cell>
                <x-filament::table.header-cell>{{ __('message.time') }}</x-filament::table.header-cell>
                <x-filament::table.header-cell>{{ __('message.status') }}</x-filament::table.header-cell>
                <x-filament::table.header-cell>{{ __('message.client') }}</x-filament::table.header-cell>
                <x-filament::table.header-cell>{{ __('message.services') }}</x-filament::table.header-cell>
                <x-filament::table.header-cell>{{ __('message.rate') }}</x-filament::table.header-cell>
            </x-slot>

            @foreach($visits as $visit)
                <x-filament::table.row wire:key="{{ $visit->id }}">
                    <x-filament::table.cell>{{ $visit->date->format('Y-m-d') }}</x-filament::table.cell>
                    <x-filament::table.cell>{{ $visit->time->format('H:i:s') }}</x-filament::table.cell>
                    <x-filament::table.cell>{{ $visit->status }}</x-filament::table.cell>
                    <x-filament::table.cell>{{ $visit->client->name ?? '-' }}</x-filament::table.cell>
                    <x-filament::table.cell>
                        @foreach($visit->services as $service)
                            <span class="inline-flex items-center justify-center min-h-6 px-2 py-0.5 text-sm font-medium rounded-xl whitespace-normal bg-primary-500/10 text-primary-700 dark:text-primary-500">
                                {{ $service->name }}
                            </span>
                        @endforeach
                    </x-filament::table.cell>
                    <x-filament::table.cell>
                        @include('filament.tables.columns.star-rating-column', ['state' => $visit->rate])
                    </x-filament::table.cell>
                </x-filament::table.row>
            @endforeach
        </x-filament::table>
    </div>

    <div class="mt-4">
        {{ $visits->links() }}
    </div>
</div>
