<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Contract;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CopyContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected array $originalStores = [];
    protected bool $skipStoresRegeneration = false;

    public function mount(int $sourceContract = null): void
    {
        abort_unless($sourceContract, 404);

        parent::mount();

        $contract = Contract::with([
            'stores.visits',
            'services'
        ])->findOrFail($sourceContract);

        // Prepare store data for the form
        $stores = $contract->stores->map(function ($store) {
            $storeData = [
                'name' => [
                    'ar' => $store->name['ar'] ?? '',
                    'en' => $store->name['en'] ?? '',
                ],
                'address' => $store->address,
                'phone' => $store->phone,
                'visits' => [],
            ];

            foreach ($store->visits as $visit) {
                $storeData['visits'][] = [
                    'date' => $visit->date,
                    'time' => $visit->time,
                    'employee_id' => $visit->employee_id,
                    'client_id' => $visit->client_id,
                ];
            }

            return $storeData;
        })->toArray();

        $this->originalStores = $stores;

        // Set form data including stores
        $formData = [
            'client_id' => $contract->client_id,
            'store_numbers' => $contract->store_numbers,
            'visits_number' => $contract->visits_number,
            'status' => false,
            'service_id' => $contract->services->pluck('id')->toArray(),
            'stores' => $stores,
        ];

        $this->form->fill($formData);
        $this->data = $formData;

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
