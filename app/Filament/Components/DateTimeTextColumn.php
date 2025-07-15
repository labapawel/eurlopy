<?php

namespace App\Filament\Components;

use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Closure;

class DateTimeTextColumn extends TextColumn
{
    protected string $customDateFormat = 'Y-m-d H:i:s';
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->formatStateUsing(function ($state) {
            if (!$state) {
                return null;
            }
            
            try {
                return Carbon::parse($state)->format($this->customDateFormat);
            } catch (\Exception $e) {
                return $state;
            }
        });
    }
    
    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();
        
        return $static;
    }
    
    public function customFormat(string $format): static
    {
        $this->customDateFormat = $format;
        
        // Zaktualizuj formatowanie
        $this->formatStateUsing(function ($state) {
            if (!$state) {
                return null;
            }
            
            try {
                return Carbon::parse($state)->format($this->customDateFormat);
            } catch (\Exception $e) {
                return $state;
            }
        });
        
        return $this;
    }
    
    public function dateTime(Closure|string|null $format = null, ?string $timezone = null): static
    {
        return $this->customFormat('d.m.Y H:i:s');
    }
    
    public function dateOnly(): static
    {
        return $this->customFormat('d.m.Y');
    }
    
    public function timeOnly(): static
    {
        return $this->customFormat('H:i:s');
    }
    
    public function since(?string $timezone = null): static
    {
        $this->formatStateUsing(function ($state) {
            if (!$state) {
                return null;
            }
            
            try {
                return Carbon::parse($state)->diffForHumans();
            } catch (\Exception $e) {
                return $state;
            }
        });
        
        return $this;
    }
    
    public function smart(): static
    {
        $this->formatStateUsing(function ($state) {
            if (!$state) return null;
            
            try {
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
            } catch (\Exception $e) {
                return $state;
            }
        });
        
        return $this;
    }
}