<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\PartnerResource\Pages;
use App\Filament\Client\Resources\PartnerResource\RelationManagers;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
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
                //
            ]);
    }
public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label(__('message.id'))
                ->sortable()
                ->searchable(),
            Tables\Columns\Layout\Stack::make([
                Tables\Columns\ImageColumn::make('logo')
                    ->height('100%')
                    ->width('100%'),
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold),
                    Tables\Columns\TextColumn::make('link')
                        ->formatStateUsing(fn (string $state): string => str($state)->after('://')->ltrim('www.')->trim('/'))
                        ->color('gray')
                        ->limit(30),
                ]),
            ])->space(3),
            Tables\Columns\Layout\Panel::make([
                Tables\Columns\Layout\Split::make([
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
                        ->searchable(),
                ]),
            ])->collapsible(),
        ])
        ->filters([
            //
        ])
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
        ])
        ->paginated([
            18,
            36,
            72,
            'all',
        ])
        ->actions([
            Tables\Actions\Action::make('visit')
                ->label('Visit link')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn (Partner $record): string => '#' . urlencode($record->link)),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
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
