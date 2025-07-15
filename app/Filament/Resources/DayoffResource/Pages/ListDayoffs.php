<?php

namespace App\Filament\Resources\DayoffResource\Pages;

use App\Filament\Resources\DayoffResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDayoffs extends ListRecords
{
    protected static string $resource = DayoffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
