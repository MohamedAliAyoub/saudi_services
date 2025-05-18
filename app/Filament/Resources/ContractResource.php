<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\Traits\HasClientBreadcrumbs;
use App\Models\Contract;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('client_id')
                    ->default(function () {
                        return request()->get('client_id');
                    }),

                Section::make(__('message.contract_details'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('store_numbers')
                                    ->label(__('message.store_numbers'))
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::updateStores($state, $get, $set);
                                    }),

                                TextInput::make('visits_number')
                                    ->label(__('message.visits_number'))
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::updateStores(null, $get, $set);
                                    }),

                                Select::make('service_id')
                                    ->label(__('message.service'))
                                    ->relationship('services')
                                    ->options(Service::pluck('name', 'id')->toArray())
                                    ->multiple()
                                    ->required(),
                                Forms\Components\Toggle::make('status')
                                    ->label(__('message.active_contract'))
                                    ->default(false)
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        // Only validate when toggling to active state
                                        if ($state === true) {
                                            $clientId = $get('client_id');

                                            if ($clientId) {
                                                // Check if this client already has an active contract
                                                $existingContract = Contract::where('client_id', $clientId)
                                                    ->where('status', 'active')  // Check for 'active' status, not boolean
                                                    ->when(request()->route('record'), function ($query, $recordId) {
                                                        // Exclude current record when editing
                                                        return $query->where('id', '!=', $recordId);
                                                    })
                                                    ->first();

                                                if ($existingContract) {
                                                    Notification::make('active-contract-exists')
                                                        ->danger()
                                                        ->title(__('message.active_contract_exists'))
                                                        ->body(__('message.cannot_activate_contract'))
                                                        ->persistent()
                                                        ->send();

                                                    // Force toggle back to inactive
                                                    $set('status', false);
                                                }
                                            }
                                        }
                                    })
                                    ->dehydrateStateUsing(fn($state) => $state ? 'active' : 'inactive')
                                    ->dehydrated(true),

                                DatePicker::make('contract_create_date')
                                    ->label(__('message.contract_create_date'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::validateContractDates($state, $get('contract_end_date'), $get('client_id'), $set);
                                        self::updateStores(null, $get, $set);
                                    }),

                                DatePicker::make('contract_end_date')
                                    ->label(__('message.contract_end_date'))
                                    ->required()
                                    ->reactive()
                                    ->minDate(fn(callable $get) => $get('contract_create_date'))
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::validateContractDates($get('contract_create_date'), $state, $get('client_id'), $set);
                                        self::updateStores(null, $get, $set);
                                    }),


                            ]),

                        Forms\Components\Repeater::make('stores')
                            ->label(__('message.stores'))
                            ->relationship('stores')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        TextInput::make('name.en')
                                            ->label(__('message.name_en'))
                                            ->required(),

                                        TextInput::make('name.ar')
                                            ->label(__('message.name_ar'))
                                            ->required(),

                                        TextInput::make('address')
                                            ->label(__('message.address'))
                                            ->required(),

                                        TextInput::make('phone')
                                            ->label(__('message.phone'))
                                            ->required(),
                                        Select::make('employee_id')
                                            ->label(__('message.employee'))
                                            ->options(\App\Models\Employee::pluck('name', 'id')->toArray())
                                            ->required(fn (string $context): bool => $context === 'create')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire) {
                                                // Get the current visits array from the store
                                                $visitsPath = 'visits';
                                                $visits = $get($visitsPath) ?? [];

                                                // Update employee_id for all visits in this store
                                                foreach ($visits as $visitIndex => $visit) {
                                                    $set("{$visitsPath}.{$visitIndex}.employee_id", $state);
                                                }
                                            }),
                                    ]),

                                Section::make(__('message.visits'))
                                    ->schema([
                                        Forms\Components\Repeater::make('visits')
                                            ->label(false)
                                            ->relationship('visits')
                                            ->schema([
                                                Grid::make(3)
                                                    ->schema([
                                                        Forms\Components\Hidden::make('client_id')
                                                            ->default(function () {
                                                                return request()->get('client_id');
                                                            }),

                                                        DatePicker::make('date')
                                                            ->label(__('message.date'))
                                                            ->required(),

                                                        TextInput::make('time')
                                                            ->label(__('message.time'))
                                                            ->type('time')
                                                            ->required(),

                                                        Select::make('employee_id')
                                                            ->label(__('message.employee'))
                                                            ->options(function() {
                                                                return \App\Models\Employee::all()->mapWithKeys(function ($employee) {
                                                                    return [$employee->id => ($employee->name['ar'] ?? $employee->name) ];
                                                                })->toArray();
                                                            })                                                            ->nullable(),
                                                    ]),
                                            ])
                                            ->columns(1),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columns(1),
                    ])
                    ->columns(1)
            ]);
    }


    protected static function validateContractDates(?string $startDate, ?string $endDate, ?int $clientId, callable $set): void
    {
        if (!$startDate || !$endDate || !$clientId) {
            return;
        }

        $existingContract = Contract::where('client_id', $clientId)
            ->where('status', 'active')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('contract_create_date', '<=', $endDate)
                    ->where('contract_end_date', '>=', $startDate);
            })
            ->when(request()->route('record'), function ($query, $recordId) {
                return $query->where('id', '!=', $recordId);
            })
            ->first();

        if ($existingContract) {
            Notification::make('contract-date-overlap')
                ->danger()
                ->title(__('message.contract_date_validation_error'))
                ->body(__('message.contract_date_overlap', [
                    'start' => $existingContract->contract_create_date,
                    'end' => $existingContract->contract_end_date
                ]))
                ->persistent()
                ->send();

            $set('contract_create_date', null);
            $set('contract_end_date', null);
        }
    }

    public static function generateVisitDates($visitsPerStore, $contractStart, $contractEnd)
    {
        $dates = [];

        if ($visitsPerStore <= 0 || !$contractStart instanceof \Carbon\Carbon || !$contractEnd instanceof \Carbon\Carbon) {
            return $dates;
        }

        $totalDays = $contractEnd->diffInDays($contractStart);
        $interval = max(1, ($totalDays > 0) ? floor($totalDays / max(1, $visitsPerStore - 1)) : 1);

        for ($i = 0; $i < $visitsPerStore; $i++) {
            $visitDate = $contractStart->copy()->addDays($interval * $i)->format('Y-m-d');
            $dates[] = ['date' => $visitDate, 'time' => '09:00'];
        }

        return $dates;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.client'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('store_numbers')
                    ->label(__('message.store_numbers'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('visits_number')
                    ->label(__('message.visits_number'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('contract_create_date')
                    ->label(__('message.contract_create_date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contract_end_date')
                    ->label(__('message.contract_end_date'))
                    ->date()
                    ->sortable(),

               Tables\Columns\IconColumn::make('status')
                   ->label(__('message.status'))
                   ->boolean()
                   ->getStateUsing(fn (Contract $record): bool => $record->status === 'active')
                   ->trueIcon('heroicon-o-check-circle')
                   ->falseIcon('heroicon-o-x-circle')
                   ->trueColor('success')
                   ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('copy')
                    ->label(__('message.copy_contract'))
                    ->icon('heroicon-o-document-duplicate')
                    ->url(fn($record) => route('filament.admin.resources.contracts.copy', [
                        'sourceContract' => $record->id,
                        'client_id' => request()->query('client_id')
                    ])
                    ),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You could add relation managers here for stores, etc.
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'copy' => Pages\CopyContract::route('/copy/{sourceContract}'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {


        $query = parent::getEloquentQuery();

        // Filter by client_id if provided in the request
        if (request()->has('client_id')) {
            $query->where('client_id', request()->get('client_id'));
        }

        return $query;
    }

    public static function getNavigationLabel(): string
    {
        return __('message.contracts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.contracts');
    }

    public static function getModelLabel(): string
    {
        return __('message.contract');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getBreadcrumbs()
    {
        //
    }


protected static function updateStores($state, callable $get, callable $set)
{
    $storeNumbers = (int)$get('store_numbers');
    $visitsNumber = (int)$get('visits_number');
    $contractStart = $get('contract_create_date');
    $contractEnd = $get('contract_end_date');

    // Check if we have valid inputs
    if ($storeNumbers <= 0 || $visitsNumber <= 0 || empty($contractStart) || empty($contractEnd)) {
        \Log::warning('[Update Stores in Contract] Missing or invalid input values');
        return;
    }

    try {
        $contractStart = \Carbon\Carbon::parse($contractStart);
        $contractEnd = \Carbon\Carbon::parse($contractEnd);
    } catch (\Exception $e) {
        \Log::error('[Update Stores in Contract] Error parsing contract dates', ['error' => $e->getMessage()]);
        return;
    }

    // First check for existing stores in the form data
    $currentFormStores = $get('stores') ?? [];
    $sessionStores = session('copied_stores', []);

    // Log for debugging
    \Log::info('[Update Stores in Contract] Current Data:', [
        'form_stores' => $currentFormStores,
        'session_stores' => $sessionStores,
    ]);

    $baseVisitsPerStore = (int)floor($visitsNumber / $storeNumbers);
    $extraVisits = $visitsNumber % $storeNumbers;

    // Prioritize form data over session data
    if (!empty($currentFormStores)) {
        // Update visits for existing form stores
        $updatedStores = [];
        for ($i = 0; $i < $storeNumbers; $i++) {
            $visitsForThisStore = $baseVisitsPerStore + ($i < $extraVisits ? 1 : 0);
            $visitDates = self::generateVisitDates($visitsForThisStore, $contractStart, $contractEnd);

            // Preserve existing store data if available
            if (isset($currentFormStores[$i])) {
                $store = $currentFormStores[$i];
                $store['visits'] = $visitDates;
                $updatedStores[] = $store;
            } else {
                // If we need more stores than currently exist
                $updatedStores[] = [
                    'name' => [
                        'ar' => '',
                        'en' => '',
                    ],
                    'address' => '',
                    'phone' => '',
                    'visits' => $visitDates,
                ];
            }
        }
        $set('stores', $updatedStores);
    }
    elseif (!empty($sessionStores)) {
        // Use session data for copying contracts
        $updatedStores = [];
        for ($i = 0; $i < $storeNumbers; $i++) {
            $visitsForThisStore = $baseVisitsPerStore + ($i < $extraVisits ? 1 : 0);
            $visitDates = self::generateVisitDates($visitsForThisStore, $contractStart, $contractEnd);

            if (isset($sessionStores[$i])) {
                $store = $sessionStores[$i];
                $store['visits'] = $visitDates;
                $updatedStores[] = $store;
            } else {
                // If we need more stores than in session
                $updatedStores[] = [
                    'name' => [
                        'ar' => '',
                        'en' => '',
                    ],
                    'address' => '',
                    'phone' => '',
                    'visits' => $visitDates,
                ];
            }
        }
        $set('stores', $updatedStores);
    }
    else {
        // Generate new stores if no existing data
        $stores = collect(range(1, $storeNumbers))->map(function ($index) use ($baseVisitsPerStore, $extraVisits, $contractStart, $contractEnd) {
            $visitsForThisStore = $baseVisitsPerStore + ($index <= $extraVisits ? 1 : 0);
            $visits = self::generateVisitDates($visitsForThisStore, $contractStart, $contractEnd);

            return [
                'name' => [
                    'ar' => '',
                    'en' => '',
                ],
                'address' => '',
                'phone' => '',
                'visits' => $visits,
            ];
        });

        $set('stores', $stores->toArray());
    }
}    protected static function isCopyingContract(): bool
    {
        $currentUrl = request()->url();
        return str_contains($currentUrl, '/contracts/copy/');
    }

    // تخزين بيانات المتاجر في الجلسة عند النسخ
    public function mount(int $sourceContract = null): void
    {
        abort_unless($sourceContract, 404);

        parent::mount();

        $contract = Contract::with([
            'stores.visits',
            'services'
        ])->findOrFail($sourceContract);

        // إعداد بيانات المتاجر
        $stores = $contract->stores->map(function ($store) {
            return [
                'name' => [
                    'ar' => $store->name['ar'] ?? '',
                    'en' => $store->name['en'] ?? '',
                ],
                'address' => $store->address,
                'phone' => $store->phone,
                'visits' => $store->visits->map(function ($visit) {
                    return [
                        'date' => $visit->date,
                        'time' => $visit->time,
                        'employee_id' => $visit->employee_id,
                        'client_id' => $visit->client_id,
                    ];
                })->toArray(),
            ];
        })->toArray();

        session(['copied_stores' => $stores]); // تخزين البيانات في الجلسة

        // تعبئة الفورم
        $formData = [
            'client_id' => $contract->client_id,
            'store_numbers' => $contract->store_numbers,
            'visits_number' => $contract->visits_number,
            'status' => false,
            'service_id' => $contract->services->pluck('id')->toArray(),
            'stores' => $stores,
        ];

        $this->form->fill($formData);
        $this->data = $formData;
        \Log::info('[Session Data]', ['copied_stores' => session('copied_stores')]);
    }

    // حذف بيانات الجلسة عند الحفظ
    public function create(bool $another = false): void
    {
        parent::create($another);

        // حذف بيانات المتاجر من الجلسة بعد الحفظ
        session()->forget('copied_stores');
    }

}
