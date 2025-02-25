<?php

namespace App\Filament\Resources;

use App\Enums\VisitTypeEnum;
use App\Filament\Client\Resources\VisitResource\Pages\ViewVisit;
use App\Filament\Resources\VisitResource\Pages;
use App\Models\Visit;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VisitResource\Columns\StarRatingColumn;


// this is the resource class for the Visit model in Admin
class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label(__('message.date'))
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->label(__('message.time'))
                    ->required(),
                Forms\Components\Select::make('employee_id')
                    ->label(__('message.employee'))
                    ->relationship('employee', 'name')
                    ->required(),
                Forms\Components\Select::make('service_id')
                    ->label(__('message.service'))
                    ->relationship('services', 'name')
                    ->options(['' => ''] + \App\Models\Service::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label(__('message.client'))
                    ->relationship('client', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('store_id', null)),

                Forms\Components\Select::make('store_id')
                    ->label(__('message.store'))
                    ->options(function (callable $get) {
                        $clientId = $get('client_id');
                        if ($clientId) {
                            return \App\Models\Store::where('client_id', $clientId)->pluck('name', 'id')->toArray();
                        }
                        return [];
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label(__('message.status'))
                    ->options(\App\Enums\VisitTypeEnum::asSelectArray())
                    ->default('pending'),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('message.date'))
                    ->dateTime('Y-m-d')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('message.time'))
                    ->dateTime('H:i')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('message.status'))
                    ->badge()
                    ->searchable('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label(__('message.employee'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('services.name')
                    ->label(__('message.services'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->label(__('message.store'))
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label(__('message.client'))
                    ->sortable()->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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

    public static function getNavigationLabel(): string
    {
        return __('message.visits');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->count();
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ImageEntry::make('images.full_path')
                    ->label(__('message.IMAGES'))
                    ->columnSpanFull(),
            ]);
    }
}
