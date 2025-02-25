<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('message.name'))
                    ->required(),
                Forms\Components\FileUpload::make('logo')
                    ->label(__('message.logo'))
                    ->required(),
                Forms\Components\DatePicker::make('date_from')
                    ->label(__('message.date_from'))
                    ->required(),
                Forms\Components\DatePicker::make('date_to')
                    ->label(__('message.date_to')),

                Forms\Components\Select::make('service_id')
                    ->label(__('message.service'))
                    ->relationship('services', 'name')
                    ->options(['' => ''] + \App\Models\Service::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->label(__('message.link')),
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
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('message.logo'))
                    ->circular(),
                Tables\Columns\TextColumn::make('date_from')
                    ->label(__('message.date_from'))
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_to')
                    ->label(__('message.date_to'))
                    ->date()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('services.name')
                    ->label(__('message.service'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('link')
                    ->label(__('message.link'))
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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }


    public static function getNavigationLabel(): string
    {
        return __('message.partners');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->count();
    }

    public static function getTitle(): string
    {
        return __('message.partner');
    }

    public static function getModelLabel(): string
    {
        return __('message.partner');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.partner');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-check-circle';
    }
}
