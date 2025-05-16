<?php

namespace App\Filament\Client\Resources\VisitResource\Pages;

use App\Filament\Client\Resources\VisitResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    protected function afterSave(): void
    {
        // Log for debugging
        Log::error('EditVisit afterSave method executed for record ID: ' . $this->record->id);

        // Send database notification
        Notification::make()
            ->title('Visit Updated')
            ->body('Visit has been updated successfully')
            ->icon('heroicon-o-calendar')
            ->sendToDatabase(auth()->user());
    }
}
