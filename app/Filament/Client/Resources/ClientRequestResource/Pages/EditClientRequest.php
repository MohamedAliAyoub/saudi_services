<?php

namespace App\Filament\Client\Resources\ClientRequestResource\Pages;

use App\Filament\Client\Resources\ClientRequestResource;
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
}
