<?php

namespace App\Filament\Resources\HolydayResource\Pages;

use App\Filament\Resources\HolydayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHolyday extends EditRecord
{
    protected static string $resource = HolydayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
