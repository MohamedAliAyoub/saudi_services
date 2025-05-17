<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Visit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EmployeeVisitsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('message.visits'))
            ->query(
                Visit::query()
                    ->where('employee_id', auth()->id())
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.client')),
                Tables\Columns\TextColumn::make('store.name')
                    ->label(__('message.store')),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.visit_date'))
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->badge()
              ->color(fn ($state): string =>
                  $state instanceof \App\Enums\VisitTypeEnum
                      ? $state->getColor()
                      : 'secondary'
              ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->paginated([10, 25, 50]);
    }
}
