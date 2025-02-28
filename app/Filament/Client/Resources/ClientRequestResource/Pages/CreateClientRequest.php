<?php

namespace App\Filament\Client\Resources\ClientRequestResource\Pages;

use App\Filament\Client\Resources\ClientRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClientRequest extends CreateRecord
{
    protected static string $resource = ClientRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
