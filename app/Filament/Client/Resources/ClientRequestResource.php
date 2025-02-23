<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ClientRequestResource\Pages;
use App\Filament\Client\Resources\ClientRequestResource\RelationManagers;
use App\Models\ClientRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientRequestResource extends Resource
{
    protected static ?string $model = ClientRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label(__('SERVICE'))
                    ->required()
                    ->relationship('service', 'name'),
                Forms\Components\Select::make('store_id')
                    ->label(__('STORE'))
                    ->required()
                    ->relationship('store', 'name'),
                Forms\Components\TextInput::make('comment')
                    ->label(__('COMMENT'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.client'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label(__('message.service'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->label(__('message.store'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('message.comment'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('message.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('message.updated_at')),
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
            'index' => Pages\ListClientRequests::route('/'),
            'create' => Pages\CreateClientRequest::route('/create'),
            'edit' => Pages\EditClientRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.CLIENT_REQUESTS');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('id' , auth()->id())->count();
    }


    public static function getModelLabel(): string
    {
        return __('message.CLIENT_REQUEST');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.CLIENT_REQUEST');

    }

}
