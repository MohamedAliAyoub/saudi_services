<?php

namespace App\Filament\Resources\VisitResource\Pages;

use App\Enums\VisitTypeEnum;
use App\Filament\Resources\VisitResource;
use App\Models\User;
use App\Models\VisitStatusLog;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    public $originalData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->originalData = $data;
        return $data;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {


        if ($this->record->employee_id !== $this->originalData['employee_id']) {
            VisitStatusLog::query()->create([
                'visit_id'=> $this->record->id,
                'user_id'=> $this->record->client_id,
                'status' => VisitTypeEnum::IN_PROGRESS
            ]);


//         Write to a specific file for debugging
//            file_put_contents(
//                storage_path('logs/visit-debug.log'),
//                'afterEdit executed at ' . now() . ' for record ID: ' . $this->record->client . PHP_EOL,
//                FILE_APPEND
//            );

            // client notification
            Notification::make()
                ->title(__('message.emergency_visit_received'))
                ->body(__('message.emergency_visit_created_received', [
                    'client_name' => auth()->user()->name,
                    'branch_name' => $this->record->store->address,
                ]))
                ->icon('heroicon-o-calendar')
                ->actions([
                    Action::make('edit')
                        ->label(__('message.view_details'))
                        ->url(route('filament.client.resources.visits.index'))
                        ->button()
                ])
                ->sendToDatabase($this->record->client);

            // employee notification
            Notification::make()
                ->title(__('message.emergency_visit_assigned_to_employee'))
                ->body(__('message.emergency_visit_created_assigned', [
                    'client_name' => auth()->user()->name,
                    'branch_name' => $this->record->store->address,
                ]))
                ->icon('heroicon-o-calendar')
                ->actions([
                    Action::make('edit')
                        ->label(__('message.view_details'))
                        ->url(route('filament.client.resources.visits.index'))
                        ->button()
                ])
                ->sendToDatabase($this->record->client);
        }
    }

}
