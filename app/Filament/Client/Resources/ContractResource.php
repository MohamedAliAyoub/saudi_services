<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\ContractResource\Pages;
use App\Filament\Client\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('message.id'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('store_numbers')
                    ->label(__('message.store_numbers')),
                Tables\Columns\TextColumn::make('visits_number')
                    ->label(__('message.visits_number')),
                Tables\Columns\TextColumn::make('contract_create_date')
                    ->label(__('message.contract_create_date'))
                    ->date(),
                Tables\Columns\TextColumn::make('contract_end_date')
                    ->label(__('message.contract_end_date'))
                    ->date(),
                Tables\Columns\IconColumn::make('status')
                    ->label(__('message.status'))
                    ->boolean()
                    ->getStateUsing(fn(Contract $record): bool => $record->status === 'active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->label(__('message.stores')),
                // Remove EditAction and DeleteAction
            ])
            ->bulkActions([
                // Remove bulk actions
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'view' => Pages\ViewContract::route('/{record}'),            // Remove 'create' and 'edit' pages
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('client_id', auth()->id())
            ->with([
                'stores' => fn($query) => $query->withCount('visits'),
                'stores.visits',
                'services',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClientContractStoresRelationManager::class,
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.contracts');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.contracts');
    }

    public static function getModelLabel(): string
    {
        return __('message.contract');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('client_id', auth()->id())->count();
    }
}
