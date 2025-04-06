<?php

namespace App\Filament\Components;

use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use App\Filament\Resources\ClientResource;

class ContractDetailsComponent
{
    public static function make(): Section
    {
        return Section::make(__('message.contract_details'))
//            ->relationship('contract')
            ->schema([
                TextInput::make('store_numbers')
                    ->label(__('message.store_numbers'))
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $get, callable $set) => self::updateVisitDates($get, $set)),

                TextInput::make('visits_number')
                    ->label(__('message.visits_number'))
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $get, callable $set) => self::updateVisitDates($get, $set)),
                Forms\Components\Select::make('service_id')
                    ->label(__('message.service'))
                    ->relationship('services', 'name')
                    ->options(['' => ''] + \App\Models\Service::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),

                DatePicker::make('contract_create_date')
                    ->label(__('message.contract_create_date'))
                    ->prefix(__('message.start_date'))
                    ->native(false)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $get, callable $set) => self::updateVisitDates($get, $set)),

                DatePicker::make('contract_end_date')
                    ->label(__('message.contract_end_date'))
                    ->prefix(__('message.end_date'))
                    ->native(false)
                    ->required()
                    ->reactive()
                    ->minDate(fn(callable $get) => $get('contract_create_date'))
                    ->afterStateUpdated(fn($state, callable $get, callable $set) => self::updateVisitDates($get, $set)),

                Hidden::make('status')
                    ->default('active'),
            ])
            ->columnSpanFull()
            ->columns(5);
    }

  protected static function updateVisitDates(callable $get, callable $set)
{
    // Fetch values from the relationship explicitly
    $storeNumbers = (int) $get('store_numbers');
    $visitsNumber = (int) $get('visits_number');
    $contractStart = $get('contract_create_date');
    $contractEnd = $get('contract_end_date');

    // Ensure valid values
    if ($storeNumbers <= 0 || $visitsNumber <= 0 || empty($contractStart) || empty($contractEnd)) {
        return;
    }

    try {
        // Convert to Carbon instances
        $contractStart = \Carbon\Carbon::parse($contractStart);
        $contractEnd = \Carbon\Carbon::parse($contractEnd);
    } catch (\Exception $e) {
        return; // Prevent errors if date conversion fails
    }

    // Calculate base visits per store and distribute extra visits
    $baseVisitsPerStore = (int) floor($visitsNumber / $storeNumbers);
    $extraVisits = $visitsNumber % $storeNumbers; // The remaining visits to distribute

    // Generate updated stores
    $updatedStores = collect(range(1, $storeNumbers))->map(function ($index) use ($baseVisitsPerStore, $extraVisits, $contractStart, $contractEnd) {
        $visitsForThisStore = $baseVisitsPerStore + ($index <= $extraVisits ? 1 : 0); // Distribute remainder
        return [
            'visits_number' => $visitsForThisStore,
            'visits' => ClientResource::generateVisitDates($visitsForThisStore, $contractStart, $contractEnd)
        ];
    });

    // Update stores state on the client
    $set('stores', $updatedStores->toArray());
}

    public function mount(Client $record)
    {
        $this->record = $record;
        $this->updateVisitDates(); // Ensure it updates before rendering
    }

}
