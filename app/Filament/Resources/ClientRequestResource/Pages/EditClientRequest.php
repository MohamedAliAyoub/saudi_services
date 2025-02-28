<?php

namespace App\Filament\Resources\ClientRequestResource\Pages;

use App\Filament\Resources\ClientRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientRequest extends EditRecord
{
    protected static string $resource = ClientRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}
