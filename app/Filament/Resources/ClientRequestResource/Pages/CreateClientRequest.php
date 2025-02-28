<?php

namespace App\Filament\Resources\ClientRequestResource\Pages;

use App\Filament\Resources\ClientRequestResource;
use Filament\Actions;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;

class CreateClientRequest extends CreateRecord
{
    protected static string $resource = ClientRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
