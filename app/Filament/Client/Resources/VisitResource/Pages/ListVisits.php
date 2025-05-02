<?php

namespace App\Filament\Client\Resources\VisitResource\Pages;

use App\Filament\Client\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('message.add_emergency_visit')),
        ];
    }

}
