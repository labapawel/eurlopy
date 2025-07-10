<?php

namespace App\Filament\Resources\HolydayResource\Pages;

use App\Filament\Resources\HolydayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHolydays extends ListRecords
{
    protected static string $resource = HolydayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
