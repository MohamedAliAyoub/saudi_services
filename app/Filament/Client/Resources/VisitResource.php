<?php

namespace App\Filament\Client\Resources;

use App\Enums\VisitTypeEnum;
use App\Filament\Client\Resources\VisitResource\Pages;
use App\Forms\Components\rateInput;
use App\Models\Client;
use App\Models\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Textarea;
use App\Models\Service;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        if ($form->getRecord() && $form->getRecord()->exists) {
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
        return $form
            ->schema([
                Forms\Components\Select::make('service_id')
                    ->label(__('message.services'))
                    ->relationship('services', 'name')
                    ->options(['' => ''] + Service::query()->pluck('name', 'id')->toArray())
                    ->multiple()
                    ->required(),
                Forms\Components\Select::make('store_id')
                    ->label(__('message.store'))
                    ->options(function (callable $get) {
                        return auth()->user()?->storesWithActiveContracts()->pluck('stores.address', 'stores.id')->toArray() ?? [];
                    })
                    ->required(),
                Forms\Components\TextInput::make('emergency_comment')
                    ->label(__('message.emergency_visit_comment')),
                Forms\Components\DatePicker::make('date')
                    ->label(__('message.date'))
                    ->required(),

                Forms\Components\TimePicker::make('time')
                    ->label(__('message.time'))
                    ->required(),
                Forms\Components\Hidden::make('status')
                    ->default(VisitTypeEnum::EMERGENCY),
                Forms\Components\Hidden::make('is_emergency')
                    ->default(1),

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
            Tables\Columns\TextColumn::make('date')
                ->label(__('message.date'))
                ->dateTime('Y-m-d'),
            Tables\Columns\TextColumn::make('time')
                ->label(__('message.time'))
                ->dateTime('H:i:s'),
            Tables\Columns\TextColumn::make('store.address')
                ->label(__('message.store'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label(__('message.status'))
                ->searchable('status')
                ->sortable(),
            Tables\Columns\TextColumn::make('client.name')
                ->label(__('message.client'))
                ->searchable()
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
                ->limit(40)
                ->tooltip(fn($record) => $record->comment),
            Tables\Columns\TextColumn::make('emergency_comment')
                ->label(__('message.emergency_visit_comment'))
                ->limit(40)
                ->tooltip(fn($record) => $record->comment),
        ])
        ->filters([
            Tables\Filters\Filter::make('store')
                ->label(__('message.store'))
                ->query(fn (Builder $query, array $data) => $query->whereHas('store', function (Builder $query) use ($data) {
                    $query->where('address', 'like', '%' . $data['value'] . '%')
                        ->orWhere('name', 'like', '%' . $data['value'] . '%');
                }))
                ->form([
                    Forms\Components\TextInput::make('value')
                        ->label(__('message.store')),
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->label(__('message.rate')),
            Tables\Actions\ViewAction::make(),
        ])
        ->headerActions([]) // Explicitly define empty header actions
        ->emptyStateActions([]); // Explicitly define empty state actions
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
            'create' => Pages\CreateVisit::route('/create'),
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
        return static::getModel()::query()->where('client_id', auth()->id())->count();
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

        Visit::updateStatus();
        $query = parent::getEloquentQuery()
            ->where('client_id', auth()->id())
            ->with(['store', 'client', 'employee', 'services', 'images'])
            ->orderByRaw("status = ? DESC", [VisitTypeEnum::EMERGENCY->value])
            ->orderBy('id', 'desc');

        if (request()->has('store_id')) {
            $query->where('store_id', request('store_id'));
        }
        return $query;

    }
}
