<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): string
    {
        // Get client_id from request or from the form data
        $clientId = request()->get('client_id') ?? $this->data['client_id'] ?? null;

        if ($clientId) {
            return $this->getResource()::getUrl('index', ['client_id' => $clientId]);
        }

        return $this->getResource()::getUrl('index');
    }
}
