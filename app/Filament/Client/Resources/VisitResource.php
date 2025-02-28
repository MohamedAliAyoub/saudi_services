<?php

namespace App\Filament\Client\Resources;

use App\Enums\VisitTypeEnum;
use App\Filament\Client\Resources\VisitResource\Pages;
use App\Filament\Client\Resources\VisitResource\RelationManagers;
use App\Forms\Components\rateInput;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Query\Builder;

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
                rateInput::make('rate')
                    ->label(__('message.rate'))
                    ->nullable(),
                Textarea::make('comment')
                    ->label(__('message.comment'))
                    ->minLength(2)
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.date'))
                    ->dateTime('Y-m-d'),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('message.time'))
                    ->dateTime('H:i:s'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->searchable('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('message.employee'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('services.name')
                    ->label(__('message.services'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate')
                    ->label(__('message.rate'))
                    ->sortable()
                    ->searchable()
                    ->view('filament.tables.columns.star-rating-column'),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('message.comment'))
                    ->limit(40) // Truncate comments longer than 50 characters
                    ->tooltip(fn($record) => $record->comment), // Show full comment on hover

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('message.status'))
                    ->options(VisitTypeEnum::asSelectArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('message.rate')),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                // No bulk actions
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('images.full_path')
                    ->label(__('message.IMAGES'))
                    ->columnSpanFull(),
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
//            'edit' => Pages\EditVisit::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.VISITS');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('id', auth()->id())->count();
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

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-arrow-right-circle';
    }


// In `app/Filament/Client/Resources/VisitResource.php`

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        Visit::updateStatus();
        return parent::getEloquentQuery()->orderBy('id', 'desc');
    }
}
