<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListContracts extends ListRecords
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(function () {
                    // If client_id is in the request, include it in the create URL
                    if (request()->has('client_id')) {
                        return static::getResource()::getUrl('create', [
                            'client_id' => request()->get('client_id')
                        ]);
                    }

                    return static::getResource()::getUrl('create');
                }),
        ];
    }
}
