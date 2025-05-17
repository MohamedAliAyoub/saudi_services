<?php

namespace App\Filament\Employee\Resources\VisitResource\Pages;

use App\Filament\Employee\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    protected function getRedirectUrl(): string
    {
        return VisitResource::getUrl('index');
    }

}

