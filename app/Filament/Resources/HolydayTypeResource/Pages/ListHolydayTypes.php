<?php

namespace App\Filament\Resources\HolydayTypeResource\Pages;

use App\Filament\Resources\HolydayTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolydayTypes extends ListRecords
{
    protected static string $resource = HolydayTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
