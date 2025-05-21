<?php

namespace App\Filament\Client\Resources\ContractResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class ClientContractStoresRelationManager extends RelationManager
{
    protected static string $relationship = 'stores';


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('translated_name')
                    ->label(__('message.name')),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.address')),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('message.phone')),
                Tables\Columns\TextColumn::make('visits_count')
                    ->label(__('message.visits_number')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('viewVisits')
                    ->label(__('message.visits'))
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.client.resources.visits.index', [
                        'store_id' => $record->id,
                    ]))

            ]);
    }


    public function getRelations(): array
    {
        return [
            StoreVisitsRelationManager::class
        ];
    }
}
