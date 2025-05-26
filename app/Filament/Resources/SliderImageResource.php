<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderImageResource\Pages;
use App\Models\SliderImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class SliderImageResource extends Resource
{
    use Translatable;

    protected static ?string $model = SliderImage::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('message.slider_image'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('message.title'))
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label(__('message.description'))
                            ->rows(3),

                        Forms\Components\TextInput::make('order')
                            ->label(__('message.order'))
                            ->integer()
                            ->default(0),

                        Forms\Components\Toggle::make('active')
                            ->label(__('message.active'))
                            ->default(true),

                        Forms\Components\SpatieMediaLibraryFileUpload::make('slider_image')
                            ->label(__('message.image'))
                            ->collection('slider_image')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->directory('sliders')
                            ->visibility('public')
                            ->maxSize(5120)
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('slider_image')
                    ->label(__('message.image'))
                    ->collection('slider_image')
                    ->width(150)
                    ->height(100),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('message.title'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('order')
                    ->label(__('message.order'))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('active')
                    ->label(__('message.active'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('message.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label(__('message.active'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSliderImages::route('/'),
            'create' => Pages\CreateSliderImage::route('/create'),
            'edit' => Pages\EditSliderImage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('message.slider_images');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getTitle(): string
    {
        return __('message.slider_image');
    }

    public static function getModelLabel(): string
    {
        return __('message.slider_image');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.slider_images');
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-photo';
    }
}
