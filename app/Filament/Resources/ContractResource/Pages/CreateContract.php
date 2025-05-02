<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Client;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure client_id is set from the request if not in the form data
        if (empty($data['client_id']) && request()->has('client_id')) {
            $data['client_id'] = request()->get('client_id');

            // Validate that the client exists
            $client = Client::find($data['client_id']);
            if (!$client) {
                Log::error('Invalid client_id specified', ['client_id' => $data['client_id']]);
                Notification::make('invalid-client')
                    ->danger()
                    ->title('Invalid Client')
                    ->body('The specified client does not exist')
                    ->persistent()
                    ->send();
            }
        }

        // If we still don't have a client_id, log an error
        if (empty($data['client_id'])) {
            Log::error('Missing client_id in contract creation');
            Notification::make('missing-client')
                ->danger()
                ->title('Missing Client')
                ->body('Client information is required to create a contract')
                ->persistent()
                ->send();
        }

        return $data;
    }

    protected function onValidationError(ValidationException $exception): void
    {
        $errors = $exception->errors();
        Log::error('Contract validation failed', ['errors' => $errors]);

        // Create a formatted list of validation errors
        $errorMessage = 'The following errors occurred:';
        foreach ($errors as $field => $messages) {
            $errorMessage .= "\n• " . $field . ': ' . implode(', ', $messages);
        }

        Notification::make('validation-error')
            ->danger()
            ->title('Validation Error')
            ->body($errorMessage)
            ->persistent()
            ->send();
    }

    protected function onCreateException(\Exception $exception): void
    {
        Log::error('Error creating contract', [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);

        $errorMessage = 'An error occurred while creating the contract.';

        if ($exception instanceof QueryException) {
            $errorMessage = 'Database error: ' . $this->simplifyDatabaseError($exception->getMessage());
        }

        Notification::make('create-exception')
            ->danger()
            ->title('Error')
            ->body($errorMessage)
            ->persistent()
            ->send();
    }

    private function simplifyDatabaseError(string $error): string
    {
        // Extract useful info from DB errors for better user messages
        if (str_contains($error, 'foreign key constraint fails')) {
            return 'Invalid relationship data provided';
        } elseif (str_contains($error, 'Duplicate entry')) {
            return 'A record with this information already exists';
        }

        return 'Please check your form data and try again';
    }
}
