<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Contract;
use Filament\Actions\CreateAction;
use Filament\Actions\LocaleSwitcher;
use Filament\Forms\Components\Actions;
use Filament\Resources\Pages\CreateRecord;
use JetBrains\PhpStorm\NoReturn;

class CreateClient extends CreateRecord
{

    protected static string $resource = ClientResource::class;

//    protected function afterCreate(): void
//    {
//        $client = $this->record; // ده العميل اللي لسه محفوظ
//               dd($client);
//
//
////        if ($client->activeContract) {
////            foreach ($client->activeContract->stores as $store) {
////                $store->update(['client_id' => $client->id]);
////
////                foreach ($store->visits as $visit) {
////                    $visit->update(['client_id' => $client->id]);
////                }
////            }
////        }
//    }


}
