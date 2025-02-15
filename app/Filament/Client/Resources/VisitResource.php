<?php

namespace App\Filament\Client\Resources;

use App\Enums\VisitTypeStatus;
use App\Filament\Client\Resources\VisitResource\Pages;
use App\Filament\Client\Resources\VisitResource\RelationManagers;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label(__('message.DATE'))
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->label(__('message.TIME'))
                    ->required(),

                Forms\Components\BelongsToSelect::make('service_id')
                    ->label(__('message.SERVICE'))
                    ->relationship('service', 'name')
                    ->required(),
                Forms\Components\BelongsToSelect::make('store_id')
                    ->label(__('message.STORE'))
                    ->relationship('store', 'name', fn(Builder $query) => $query->where('client_id', auth()->id()))
                    ->required(),
                Forms\Components\BelongsToSelect::make('employee_id')
                    ->label(__('message.EMPLOYEE'))
                    ->relationship('employee', 'name'),
                Forms\Components\Textarea::make('comment')
                    ->label(__('message.COMMENT'))
                    ->nullable(),
                Forms\Components\Hidden::make('client_id')
                    ->default(auth()->id())
                    ->required(),
                Forms\Components\Hidden::make('status')
                    ->default("pending")
                    ->required(),

            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.DATE'))
                    ->dateTime('Y-m-d'),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('message.TIME'))
                    ->dateTime('H:i:s'),
                Tables\Columns\TextColumn::make('translated_status')
                    ->label(__('message.STATUS'))
                   ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('message.COMMENT')),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('message.EMPLOYEE'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label(__('message.SERVICE'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions
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
            'index' => Pages\ListVisits::route('/'),
            'view' => Pages\ViewVisit::route('/{record}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.VISITS');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('id' , auth()->id())->count();
    }
    public static function getTitle(): string
    {
        return __('message.visit');
    }

    public static function getModelLabel(): string
    {
        return __('message.visit');
    }
    public static function getPluralModelLabel(): string
    {
        return __('message.visit');
    }


}
