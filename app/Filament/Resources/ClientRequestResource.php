<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientRequestResource\Pages;
use App\Filament\Resources\ClientRequestResource\RelationManagers;
use App\Models\ClientRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientRequestResource extends Resource
{
    protected static ?string $model = ClientRequest::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label(__('message.service'))
                    ->relationship('services', 'name')
                    ->options(['' => ''] + \App\Models\Service::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label(__('message.client'))
                    ->required()
                    ->relationship('client', 'name'),
                Forms\Components\Select::make('store_id')
                    ->label(__('message.store'))
                    ->required()
                    ->relationship('store', 'name'),
                Forms\Components\Select::make('status')
                    ->label(__('message.status'))
                    ->options(\App\Enums\VisitTypeEnum::asSelectArray())
                    ->default('pending'),
                Forms\Components\Textarea::make('comment')
                    ->label(__('message.comment'))
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label(__('message.date')),
                Forms\Components\TimePicker::make('time')
                    ->label(__('message.time')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->label(__('message.store'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('services.name')
                    ->label(__('message.services'))
                    ->searchable()
                    ->sortable()
                    ->limit(30) // Truncate comments longer than 50 characters
                    ->tooltip(fn($record) => $record->comment), // Show full comment on hover,
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('message.comment'))
                    ->searchable()
                    ->limit(40) // Truncate comments longer than 50 characters
                    ->tooltip(fn($record) => $record->comment), // Show full comment on hover,
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('message.time'))
                    ->time()
                    ->sortable(),
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
        return static::getModel()::query()->where('id', auth()->id())->count();
    }


    public static function getModelLabel(): string
    {
        return __('message.CLIENT_REQUEST');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.CLIENT_REQUEST');

    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id' , 'desc');
    }




}
