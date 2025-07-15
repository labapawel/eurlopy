<?php

namespace App\Filament\Traits;

use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;

trait DateTimeColumnHelper
{
    public static function dateTimeColumn(string $name, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(fn ($state) => 
                $state ? Carbon::parse($state)->format('d.m.Y H:i:s') : null
            )
            ->sortable();
    }
    
    public static function dateColumn(string $name, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(fn ($state) => 
                $state ? Carbon::parse($state)->format('d.m.Y') : null
            )
            ->sortable();
    }
    
    public static function timeOnlyColumn(string $name, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(fn ($state) => 
                $state ? Carbon::parse($state)->format('H:i:s') : null
            );
    }
    
    public static function sinceColumn(string $name, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(fn ($state) => 
                $state ? Carbon::parse($state)->diffForHumans() : null
            );
    }
    
    public static function customDateColumn(string $name, string $format, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(fn ($state) => 
                $state ? Carbon::parse($state)->format($format) : null
            )
            ->sortable();
    }
    
    public static function smartDateColumn(string $name, ?string $label = null): TextColumn
    {
        return TextColumn::make($name)
            ->label($label ?? str($name)->title())
            ->formatStateUsing(function ($state) {
                if (!$state) return null;
                
                $date = Carbon::parse($state);
                
                if ($date->isToday()) {
                    return 'Dzisiaj o ' . $date->format('H:i');
                }
                
                if ($date->isTomorrow()) {
                    return 'Jutro o ' . $date->format('H:i');
                }
                
                if ($date->isYesterday()) {
                    return 'Wczoraj o ' . $date->format('H:i');
                }
                
                if ($date->year === now()->year) {
                    return $date->format('d.m H:i');
                }
                
                return $date->format('d.m.Y H:i');
            })
            ->sortable();
    }
}

// Przykład użycia w Resource:

namespace App\Filament\Resources;

use App\Filament\Traits\DateTimeColumnHelper;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ExampleResource extends Resource
{
    use DateTimeColumnHelper;
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                self::dateTimeColumn('created_at', 'Data utworzenia'),
                self::dateColumn('updated_at', 'Data aktualizacji'),
                self::timeOnlyColumn('start_time', 'Godzina rozpoczęcia'),
                self::sinceColumn('last_login', 'Ostatnie logowanie'),
                self::smartDateColumn('event_date', 'Data wydarzenia'),
                self::customDateColumn('deadline', 'd/m/Y \o H:i', 'Termin'),
            ]);
    }
}