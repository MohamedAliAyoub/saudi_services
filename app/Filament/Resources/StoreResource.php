<?php
// app/Filament/Resources/StoreResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('message.Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label(__('message.Address'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label(__('message.Phone'))
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->nullable(),
                Forms\Components\Select::make('client_id')
                    ->label(__('message.Client'))
                    ->relationship('client', 'name')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->label(__('message.status'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('message.Name'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.Address'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('message.Phone'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.Client'))
                    ->label('Client')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('status')
                    ->label(__('message.status'))
                    ->sortable(),
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

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return 'heroicon-o-stop-circle';
    }
}
