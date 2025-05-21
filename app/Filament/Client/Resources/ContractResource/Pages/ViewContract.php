<?php

namespace App\Filament\Client\Resources\ContractResource\Pages;

use App\Filament\Client\Resources\ContractResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContract extends ViewRecord
{
    protected static string $resource = ContractResource::class;


    public function getTitle(): string
    {
        return __('message.stores');
    }
}
