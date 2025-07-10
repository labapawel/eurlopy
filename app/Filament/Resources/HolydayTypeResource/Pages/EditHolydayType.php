<?php

namespace App\Filament\Resources\HolydayTypeResource\Pages;

use App\Filament\Resources\HolydayTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolydayType extends EditRecord
{
    protected static string $resource = HolydayTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
