<?php

namespace App\Filament\Widgets;

use App\Enums\VisitTypeEnum;
use App\Models\Visit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Today\'s Visits';


    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::query()
                    ->whereDate('date', today())
                    ->with(['client', 'store'])
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.client')),
                Tables\Columns\TextColumn::make('store.address')
                    ->label(__('message.store')),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.visit_date'))
                    ->date(),
                Tables\Columns\TextColumn::make('visit_date')
                    ->label(__('message.visit_date_done'))
                    ->dateTime('Y-m-d H:i'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->badge()
                    ->color(fn($state): string => $state instanceof VisitTypeEnum
                        ? $state->getColor()
                        : 'secondary'
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('message.save_visit'))
                    ->url(fn (Visit $record): string => route('filament.admin.resources.visits.edit', ['record' => $record])),
            ]);
    }
}
