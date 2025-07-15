<?php

namespace App\Filament\Resources\DayoffResource\Pages;

use App\Filament\Resources\DayoffResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDayoff extends EditRecord
{
    protected static string $resource = DayoffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
