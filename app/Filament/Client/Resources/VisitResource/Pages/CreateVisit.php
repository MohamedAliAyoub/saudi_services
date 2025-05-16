<?php

namespace App\Filament\Client\Resources\VisitResource\Pages;

use App\Filament\Client\Resources\VisitResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Actions\Action; // Proper import
use Filament\Notifications\Notification;


class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    protected function afterCreate(): void
    {
        // Write to a specific file for debugging
//        file_put_contents(
//            storage_path('logs/visit-debug.log'),
//            'afterCreate executed at ' . now() . ' for record ID: ' . $this->record->id . PHP_EOL,
//            FILE_APPEND
//        );
        $admins = User::where('role', 'admin')->get();

        // Rest of your notification code
        Notification::make()
            ->title(__('message.emergency_visit_created'))
            ->body(__('message.emergency_visit_created_success', [
                'client_name' => auth()->user()->name,
                'branch_name' => $this->record->store->address,
            ]))
            ->icon('heroicon-o-calendar')
            ->actions([
                Action::make('edit')
                    ->label(__('message.view_details'))
                    ->url(route('filament.admin.resources.visits.index'))
                    ->button()
            ])
            ->sendToDatabase($admins);
    }

    protected function getRedirectUrl(): string
    {
        return VisitResource::getUrl('index');
    }

}
