<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model; 

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

//  // DODAJ TE METODY:
//     protected function beforeFill(): void
//     {
//         \Log::info('EditPage beforeFill', [
//             'record_data' => $this->record->toArray(),
//             'hours_per_week' => $this->record->hours_per_week
//         ]);
//     }

//     protected function beforeSave(): void
//     {
//         \Log::info('EditPage beforeSave', [
//             'form_data' => $this->form->getState(),
//             'hours_per_week' => $this->form->getState()['hours_per_week'] ?? 'NOT_SET'
//         ]);
//     }

//     protected function afterSave(): void
//     {
//         \Log::info('EditPage afterSave', [
//             'model_hours' => $this->record->hours_per_week,
//             'model_hours_type' => gettype($this->record->hours_per_week)
//         ]);
//     }

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     \Log::info('EditPage handleRecordUpdate', [
    //         'incoming_data' => $data,
    //         'hours_per_week_in_data' => $data['hours_per_week'] ?? 'NOT_SET',
    //         'hours_per_week_type' => gettype($data['hours_per_week'] ?? null)
    //     ]);
        
    //     $record->update($data);
        
    //     \Log::info('EditPage after update', [
    //         'record_hours' => $record->fresh()->hours_per_week
    //     ]);
        
    //     return $record;
    // }


}
