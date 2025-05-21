<?php

namespace App\Filament\Client\Resources\ContractResource\Pages;

use App\Filament\Client\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function canCreate(): bool
    {
        return false;
    }
}
