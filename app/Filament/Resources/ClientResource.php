<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('message.client_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label(__('message.name')),
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
                Forms\Components\Section::make(__('message.contract_details'))
                    ->relationship('activeContract')  // ✅ This ensures fields are stored in the Contract model
                    ->schema([
                        Forms\Components\TextInput::make('store_numbers')
                            ->label(__('message.store_numbers'))
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) =>
                            $set('stores', collect(range(1, (int)$state))->map(fn() => [])->toArray())
                            ),

                        Forms\Components\TextInput::make('visits_number')
                            ->numeric()
                            ->label(__('message.visits_number'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $storeCount = max(1, (int)$get('store_numbers')); // Ensure at least 1 store
                                $visitsPerStore = $state > 0 ? (int)floor($state / $storeCount) : 0;

                                // Assign visits proportionally to stores
                                $stores = collect(range(1, $storeCount))
                                    ->map(fn() => ['visits_number' => $visitsPerStore])
                                    ->toArray();

                                $set('stores', $stores);
                            }),

                        Forms\Components\DatePicker::make('contract_create_date')
                            ->label(__('message.contract_create_date'))
                            ->required(),

                        Forms\Components\DatePicker::make('contract_end_date')
                            ->label(__('message.contract_end_date'))
                            ->required(),

                        Forms\Components\Hidden::make('status')
                            ->default('active'),
                    ])
                    ->columnSpanFull()
                    ->columns(4),
                Forms\Components\Repeater::make('stores')
                    ->label(__('message.stores'))
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('message.name'))
                            ->required(),

                        Forms\Components\TextInput::make('address')
                            ->label(__('message.address'))
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label(__('message.phone'))
                            ->required(),

                        Forms\Components\TextInput::make('visits_number')
                            ->label(__('message.visits_number'))
                            ->numeric()
                            ->required(),


                        //  Visits Repeater (Keep Existing Data When Editing)
                        Forms\Components\Repeater::make('visits')
                            ->label(__('message.visits'))
                            ->relationship('visitsWithClient')
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->label(__('message.date'))
                                    ->required(),

                                Forms\Components\TextInput::make('time')
                                    ->label(__('message.time'))
                                    ->type('time')
                                    ->required(),

                                Forms\Components\Select::make('employee_id')
                                    ->label(__('message.employee'))
                                    ->relationship('employee', 'name')
                                    ->nullable(),

                                Forms\Components\Hidden::make('store_id')
                                    ->default(fn($record) => $record?->id),
                            ])
                            ->columnSpanFull()
                            ->columns(3)
                            ->reactive()
                            ->collapsed(false)
                            ->deletable()
                            ->default([])
                            ->hidden(fn(callable $get) => empty($get('visits_number'))),
                    ])
                    ->columnSpanFull()
                    ->columns(4)
                    ->reactive()
                    ->default([])
                    ->collapsed(false)
                    ->deletable(true)
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $stores = collect($state)->map(function ($store) use ($get) {
                            $contractStart = $get('activeContract.contract_create_date');
                            $contractEnd = $get('activeContract.contract_end_date');
                            $visitsNumber = $store['visits_number'] ?? 0;

                            // ✅ Preserve visits if they already exist
                            if (!empty($store['visits'])) {
                                return $store;
                            }

                            // ✅ Generate new visits only if none exist
                            if (!empty($contractStart) && !empty($contractEnd) && $visitsNumber > 0) {
                                $store['visits'] = ClientResource::generateVisitDates(
                                    $visitsNumber,
                                    $contractStart,
                                    $contractEnd
                                );
                            }

                            return $store;
                        });

                        $set('stores', $stores->toArray());
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('message.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('message.email'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('message.phone'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.address'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('message.company_name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_numbers')
                    ->label(__('message.store_numbers'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('visits_number')
                    ->label(__('message.visits_number'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_create_date')
                    ->label(__('message.contract_create_date'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_end_date')
                    ->label(__('message.contract_end_date'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_years_number')
                    ->label(__('message.contract_years_number'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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
        return parent::getEloquentQuery()->where('role', 'client');
    }


    public static function getNavigationLabel(): string
    {
        return __('message.client');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('role', 'client')->count();
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
        return __('message.client');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-user-group';
    }

    public static function generateVisitDates($visitsNumber, $startDate, $endDate)
    {
        $dates = [];
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        // ✅ Calculate interval between visits
        $interval = $end->diffInDays($start) / max(1, ($visitsNumber - 1));

        for ($i = 0; $i < $visitsNumber; $i++) {
            $visitDate = $start->copy()->addDays($interval * $i)->toDateString();
            $dates[] = ['date' => $visitDate, 'time' => '09:00'];
        }

        return $dates;
    }


}
