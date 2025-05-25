<?php

namespace App\Filament\Employee\Resources;

use App\Enums\VisitTypeEnum;
use App\Filament\Client\Resources\VisitResource\Pages\ViewVisit;
use App\Filament\Employee\Resources\VisitResource\Pages;
use App\Filament\Employee\Resources\VisitResource\RelationManagers;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;


class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        // add id without edit it just show
                        Forms\Components\TextInput::make('id')
                            ->label(__('message.id'))
                            ->disabled()
                            ->default(fn ($record) => $record ? $record->id : null),
                        Forms\Components\Checkbox::make('mark_as_complete')
                            ->label(__('message.mark_as_complete'))
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('status', 'done');
                                    $set('visit_date', now()->format('Y-m-d H:i:s'));
                                }
                            })
                            ->default(function ($record) {
                                return $record && ($record->status == \App\Enums\VisitTypeEnum::DONE || $record->status == 'done');
                            })
                            ->dehydrated(false),
                        Forms\Components\Hidden::make('status')
                            ->default('pending'),


                        Forms\Components\DateTimePicker::make('visit_date')
                            ->label(__('message.visit_date'))
                            ->seconds(true)
                            ->required(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images_before')
                            ->multiple()
                            ->collection('visit_images_before')
                            ->directory(fn($record) => 'visits/' . $record->id . '/before')
                            ->label(__('message.images_before')),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images_after')
                            ->multiple()
                            ->collection('visit_images_after')
                            ->directory(fn($record) => 'visits/' . $record->id . '/after')
                            ->label(__('message.images_after')),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images_reports')
                            ->multiple()
                            ->collection('visit_images_reports')
                            ->directory(fn($record) => 'visits/' . $record->id . '/reports')
                            ->label(__('message.images_reports')),
                    ]),
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
                    ->color(fn($state): string => $state instanceof \App\Enums\VisitTypeEnum
                        ? $state->getColor()
                        : 'secondary'
                    ),
            ])
            ->filters([
              // filter by store name
                Tables\Filters\Filter::make('store')
                    ->query(fn (Builder $query, array $data) => $query->whereHas('store', function (Builder $query) use ($data) {
                        $query->where('address', 'like', '%' . $data['value'] . '%')
                            ->orWhere('name', 'like', '%' . $data['value'] . '%');
                    }))
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label(__('message.store'))
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('message.save_visit')),


                Tables\Actions\ViewAction::make()
                    ->label(__('message.view'))
                    ->url(fn (Visit $record) => route('filament.employee.resources.visits.view', ['record' => $record])),
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
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
            'edit' => Pages\EditVisit::route('/{record}/edit'),
            'view' => ViewVisit::route('/{record}'),

        ];
    }

    public function query()
    {
        return Visit::query()
            ->where('employee_id', auth()->id())
            ->with(['client', 'store'])
            ->orderByDesc('id');
    }

    public static function getNavigationLabel(): string
    {
        return __('message.visits');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()
            ->where('employee_id', auth()->id())
            ->count();
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('employee_id', auth()->id())
            ->with(['client', 'store'])
            ->orderByRaw("status = ? DESC", [VisitTypeEnum::EMERGENCY->value])
            ->orderBy('id', 'desc');
    }

}


