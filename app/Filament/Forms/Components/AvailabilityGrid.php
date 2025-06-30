<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class AvailabilityGrid extends Field
{
    protected string $view = 'filament.forms.components.availability-grid';

    public function getDays(): array
    {
        return [
            'monday' => 'Poniedziałek',
            'tuesday' => 'Wtorek', 
            'wednesday' => 'Środa',
            'thursday' => 'Czwartek',
            'friday' => 'Piątek',
            'saturday' => 'Sobota',
            'sunday' => 'Niedziela',
        ];
    }

    public function getHours(): array
    {
        $hours = [];
        for ($i = 7; $i <= 19; $i++) {
            $hours[$i] = sprintf('%02d:00', $i);
        }
        return $hours;
    }
}