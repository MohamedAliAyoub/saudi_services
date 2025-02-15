<?php

namespace App\Filament\Client\Resources\VisitResource\Pages;

use App\Filament\Client\Resources\VisitResource;
use Filament\Resources\Pages\ViewRecord;

class ViewVisit extends ViewRecord
{
    protected static string $resource = VisitResource::class;

    public function getView(): string
    {
        return 'filament.client.resources.visit-resource.view';
    }

    protected function getTabsView(): string
    {
        return 'filament.client.resources.visit-resource.tabs';
    }
}
