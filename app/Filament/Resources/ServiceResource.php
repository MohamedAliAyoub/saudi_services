<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function ($records, $action) {
                            $failedRecords = [];
                            $successCount = 0;

                            foreach ($records as $record) {
                                try {
                                    $record->delete();
                                    $successCount++;
                                } catch (\Throwable $e) {
                                    $failedRecords[] = $record->name ?? $record->id;

                                    //         Write to a specific file for debugging
//                                    file_put_contents(
//                                        storage_path('logs/visit-debug.log'),
//                                        'delete not success ' . $e->getMessage() . PHP_EOL,
//                                        FILE_APPEND
//                                    );
                                    Notification::make()
                                        ->title(__('message.error'))
                                        ->body(__('message.cannot_delete_service_in_use', [
                                            'name' => $record->name ?? $record->id,
                                        ]))
                                        ->danger()
                                        ->send();
                                    break;
                                }
                            }

                            if (!empty($failedRecords)) {
                                $action->failure(
                                    __('message.some_services_in_use', [
                                        'services' => implode(', ', $failedRecords),
                                    ])
                                );

                                return;
                            }

                            $action->success(__('message.deleted_successfully', [
                                'count' => $successCount
                            ]));
                        }),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.services');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->count();
    }

    public static function getTitle(): string
    {
        return __('message.service');
    }

    public static function getModelLabel(): string
    {
        return __('message.service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.service');
    }


}
