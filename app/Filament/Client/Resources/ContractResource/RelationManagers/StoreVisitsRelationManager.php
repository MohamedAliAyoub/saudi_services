<?php

namespace App\Filament\Client\Resources\ContractResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StoreVisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'visits';

    protected static ?string $recordTitleAttribute = 'date';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('message.id'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.date'))
                    ->date(),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('message.time')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status')),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('message.employee'))
                    ->default('-'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
