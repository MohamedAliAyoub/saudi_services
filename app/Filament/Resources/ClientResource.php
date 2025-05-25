<?php

namespace App\Filament\Resources;

use App\Enums\UserTypeEnum;
use App\Enums\VisitTypeEnum;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;


// Correct import

use Illuminate\Support\Facades\Hash;

class ClientResource extends Resource
{
    use Translatable;

    protected static ?string $model = Client::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('message.client_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name.ar')
                            ->label(__('message.name'))
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label(__('message.email'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->label(__('message.Password'))
                            ->password()
                            ->nullable()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => !empty($state))
                            ->required(fn(string $context) => $context === 'create'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->label(__('message.phone'))
                            ->required(),
                        Forms\Components\TextInput::make('address')
                            ->label(__('message.address')),
                        Forms\Components\TextInput::make('company_name')
                            ->label(__('message.company_title')),
                        Forms\Components\FileUpload::make('image')
                            ->label(__('message.image'))
                            ->image()
                            ->directory('users')
                            ->nullable()
                            ->dehydrated(fn($state) => filled($state))  // This ensures empty states are handled correctly
                            ->disk('public')  // Explicitly set the disk
                            ->preserveFilenames(),  // Optional: preserve original filenames
                    ])->columnSpanFull()
                    ->columns(3),
                Forms\Components\Hidden::make('role')
                    ->default('client'),
                Section::make(__('message.contract_details'))
                    ->relationship('activeContract')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                TextInput::make('store_numbers')
                                    ->label(__('message.store_numbers'))
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::updateStores($state, $get, $set);
                                    }),
                                Forms\Components\Hidden::make('status')
                                    ->default('active')
                                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateClient),

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
                                    ->options(\App\Models\Service::pluck('name', 'id')->toArray())
                                    ->multiple()
                                    ->required(),


                                DatePicker::make('contract_create_date')
                                    ->label(__('message.contract_create_date'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::updateStores(null, $get, $set);
                                    }),

                                DatePicker::make('contract_end_date')
                                    ->label(__('message.contract_end_date'))
                                    ->required()
                                    ->reactive()
                                    ->minDate(fn(callable $get) => $get('contract_create_date'))
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        self::updateStores(null, $get, $set);
                                    }),

                                Forms\Components\SpatieMediaLibraryFileUpload::make('pdf_path')
                                    ->label(__('message.pdf_contract'))
                                    ->collection('contract_pdfs')
                                    ->directory('contracts/pdfs')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(10240),

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
                                                            ->default(function ($get, $record, $livewire) {
                                                                if (request()->route('record')) {
                                                                    return request()->route('record');
                                                                }
                                                                return null;
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
                                                            ->options(\App\Models\Employee::pluck('name', 'id')->toArray())
                                                            ->nullable(),
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


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('message.id'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('message.name'))
                    ->getStateUsing(fn($record) => $record->name ?? null),


                Tables\Columns\TextColumn::make('phone')
                    ->label(__('message.phone'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.address'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('message.company_title'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('activeContract.store_numbers')
                    ->label(__('message.store_numbers'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('activeContract.visits_number')
                    ->label(__('message.visits_number'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('activeContract.contract_create_date')
                    ->label(__('message.contract_create_date'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('activeContract.contract_end_date')
                    ->label(__('message.contract_end_date'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('contracts')
                    ->label(__('message.contracts'))
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('filament.admin.resources.contracts.index', ['client_id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected static function updateStores($state, callable $get, callable $set)
    {
        $storeNumbers = (int)$get('store_numbers');
        $visitsNumber = (int)$get('visits_number');
        $contractStart = $get('contract_create_date');
        $contractEnd = $get('contract_end_date');

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

        $baseVisitsPerStore = (int)floor($visitsNumber / $storeNumbers);
        $extraVisits = $visitsNumber % $storeNumbers;

        $stores = collect(range(1, $storeNumbers))->map(function ($index) use ($baseVisitsPerStore, $extraVisits, $contractStart, $contractEnd) {
            $visitsForThisStore = $baseVisitsPerStore + ($index <= $extraVisits ? 1 : 0);
            $visits = ClientResource::generateVisitDates($visitsForThisStore, $contractStart, $contractEnd);

            return [
                'name_ar' => '',
                'name_en' => '',
                'address' => '',
                'phone' => '',
                'visits' => $visits,
            ];
        });

        $set('stores', $stores->toArray());
    }

    public static function getRelations(): array
    {
        return [
//            'App\Filament\Resources\ClientResource\RelationManagers\ClientContractStoresRelationManager',
//            'App\Filament\Resources\ClientResource\RelationManagers\VisitsRelationManager',
//            'App\Filament\Resources\ClientResource\RelationManagers\ContractsRelationManager',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        // Log all executed queries
        DB::listen(function ($query) {
            \Log::info('Executed Query', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ]);
        });

        return parent::getEloquentQuery()
            ->where('role', UserTypeEnum::CLIENT)
            ->with(['activeContract.stores.visits'])
            ->orderBy('id', 'desc');
    }

    public static function getNavigationLabel(): string
    {
        return __('message.clients');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('role', UserTypeEnum::CLIENT)->count();
    }

    public static function getTitle(): string
    {
        return __('message.client');
    }

    public static function getModelLabel(): string
    {
        return __('message.client');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.clients');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-user-group';
    }

    public static function generateVisitDates($visitsPerStore, $contractStart, $contractEnd)
    {
        \Log::info('[Generate Visits Dates in Client Resource] generateVisitDates called', [
            'visitsPerStore' => $visitsPerStore,
            'contractStart' => $contractStart,
            'contractEnd' => $contractEnd,
        ]);

        $dates = [];

        if ($visitsPerStore <= 0 || !$contractStart instanceof \Carbon\Carbon || !$contractEnd instanceof \Carbon\Carbon) {
            \Log::warning('[Generate Visits Dates in Client Resource] Invalid input parameters for generateVisitDates', [
                'visitsPerStore' => $visitsPerStore,
                'contractStart' => $contractStart,
                'contractEnd' => $contractEnd,
            ]);
            return $dates;
        }

        $totalDays = $contractEnd->diffInDays($contractStart);
        $interval = max(1, ($totalDays > 0) ? floor($totalDays / max(1, $visitsPerStore - 1)) : 1);

        for ($i = 0; $i < $visitsPerStore; $i++) {
            $visitDate = $contractStart->copy()->addDays($interval * $i)->format('Y-m-d');
            $dates[] = ['date' => $visitDate, 'time' => '09:00'];
        }

        \Log::info('[Generate Visits Dates in Client Resource] Generated visit dates', ['dates' => $dates]);

        return $dates;
    }


}
