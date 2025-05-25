<?php
// app/Filament/Resources/StoreResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Client;
use App\Models\Service;
use App\Models\Store;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with('contract', 'clientThroughContract')
            ->orderByDesc('id');

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('message.id'))
                    ->sortable()
                    ->searchable(),
                TextInput::make('name.en')
                    ->label(__('message.name_en'))
                    ->required(),

                TextInput::make('name.ar')
                    ->label(__('message.name_ar'))
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label(__('message.address'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('message.phone'))
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->nullable(),
                Forms\Components\Select::make('contract_id')
                        ->label(__('message.contract'))
                        ->options(function () {
                            return \App\Models\Contract::query()
                                ->where('status', 'active')
                                ->get()
                                ->mapWithKeys(function ($contract) {
                                    return [$contract->id => $contract->name];
                                });
                        })
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $contract = \App\Models\Contract::find($state);
                                if ($contract) {
                                    $set('client_id', $contract->client_id);
                                }
                            }
                        }),

                    // Hidden field to store the client_id
                    Forms\Components\Hidden::make('client_id')
                        ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('translated_name')
                    ->label(__('message.Name'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.address'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('message.phone'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('clientThroughContract.name')
                    ->label(__('message.client'))
                    ->label('Client')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('contract.status')
                    ->label(__('message.status'))
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->contract?->status === 'active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.stores');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->count();
    }

    public static function getTitle(): string
    {
        return __('message.store');
    }

    public static function getModelLabel(): string
    {
        return __('message.store');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.store');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-stop-circle';
    }


}
