<?php

namespace App\Filament\Employee\Resources\VisitResource\Pages;

use App\Enums\VisitTypeEnum;
use App\Filament\Employee\Resources\VisitResource;
use App\Models\User;
use App\Models\VisitStatusLog;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {

        VisitStatusLog::query()->firstOrCreate([
            'visit_id' => $this->record->id,
            'user_id' => $this->record->client_id,
            'status' => VisitTypeEnum::DONE
        ]);


//         Write to a specific file for debugging
//            file_put_contents(
//                storage_path('logs/visit-debug.log'),
//                'afterEdit executed at ' . now() . ' for record ID: ' . $this->record->client . PHP_EOL,
//                FILE_APPEND
//            );

        $revivers = User::query()
            ->where('role', 'admin')
            ->orWhere('id', $this->record->client_id)
            ->get();

        // Rest of your notification code
        // First, separate users by role
        $admins = $revivers->where('role', 'admin');
        $clients = $revivers->where('role', 'client');

        // Send admin notification
        if ($admins->isNotEmpty()) {
            Notification::make()
                ->title(__('message.visit_received_done'))
                ->body(__('message.visit_received_done_message', [
                    'client_name' => $this->record->client?->name,
                    'branch_name' => $this->record->store?->address,
                ]))
                ->icon('heroicon-o-calendar')
                ->actions([
                    Action::make('edit')
                        ->label(__('message.view_details'))
                        ->url(route('filament.admin.resources.visits.view', ['record' => $this->record->id]))
                        ->button()
                ])
                ->sendToDatabase($admins);
        }

        // Send client notification
        if ($clients->isNotEmpty()) {
            Notification::make()
                ->title(__('message.visit_received_done'))
                ->body(__('message.visit_received_done_message', [
                    'client_name' => $this->record->client?->name,
                    'branch_name' => $this->record->store?->address,
                ]))
                ->icon('heroicon-o-calendar')
                ->actions([
                    Action::make('edit')
                        ->label(__('message.view_details'))
                        ->url(route('filament.client.resources.visits.view', ['record' => $this->record->id]))
                        ->button()
                ])
                ->sendToDatabase($clients);
        }
    }

   public function getTitle(): string
    {
        return __('message.edit_visit_for', [
            'store' => $this->record->store->address ?? '',
            'id' => $this->record->store->id ?? ''
        ]);
    }


}
