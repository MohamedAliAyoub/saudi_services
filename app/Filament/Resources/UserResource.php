<?php

namespace App\Filament\Resources;

use App\Enums\UserTypeEnum;
use App\Enums\VisitTypeEnum;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('message.Name'))
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label(__('message.email'))
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->label(__('message.Password'))
                    ->password()
                    ->nullable()
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => !empty($state))
                    ->required(fn(string $context) => $context === 'create'),
                Forms\Components\Select::make('role')
                    ->label(__('message.Role'))
                    ->options(UserTypeEnum::asSelectArray())
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->label(__('message.phone'))
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label(__('message.address'))
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('users')
                    ->nullable()
                    ->dehydrated(fn($state) => filled($state))  // This ensures empty states are handled correctly
                    ->disk('public')  // Explicitly set the disk
                    ->preserveFilenames()  // Optional: preserve original filenames
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('message.Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('message.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('message.Role'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('message.image'))
                    ->disk('public'),

                Tables\Columns\TextColumn::make('address')
                    ->label(__('message.address'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public
    static function getRelations(): array
    {
        return [
            //
        ];
    }

    public
    static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }


    public static function getNavigationLabel(): string
    {
        return __('message.Users');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('role', [
                UserTypeEnum::EMPLOYEE,
                UserTypeEnum::ADMIN,
            ])->orderBy('id', 'desc');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()
            ->whereIn('role', [
                UserTypeEnum::EMPLOYEE,
                UserTypeEnum::ADMIN,
            ])
            ->count();
    }

    public static function getTitle(): string
    {
        return __('message.User');
    }

    public static function getModelLabel(): string
    {
        return __('message.User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('message.User');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return 'heroicon-o-user-group';
    }
}
