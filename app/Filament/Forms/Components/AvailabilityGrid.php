<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class AvailabilityGrid extends Field
{
    protected string $view = 'filament.forms.components.availability-grid';

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->statePath($this->getName());
        
        // KOMPATYBILNOŚĆ: Różne wersje Filament
        $this->reactive(); // Podstawowa reaktywność
        
        // Dla Filament v3+ (jeśli dostępne)
        if (method_exists($this, 'deferred')) {
            $this->deferred();
        } elseif (method_exists($this, 'lazy')) {
            $this->lazy(); // Starsza wersja
        }
        
        // UPROSZCZONA walidacja - bez closure
        $this->rule('nullable');
        // $this->rule('array');

        // NOWE: Dodaj domyślną wartość
        $this->default([]);
        
        // WAŻNE: Ustaw jako live component
        $this->live(false); // Wyłącz live updates aby uniknąć conflictów
    }

    public function setState($state): static
    {
        \Log::info('AvailabilityGrid setState called', [
            'state' => $state, 
            'type' => gettype($state),
            'path' => $this->getStatePath()
        ]);
        
        // KLUCZOWE: Konwersja JSON string na array PRZED normalizacją
        $normalized = $this->normalizeStateValue($state);
        
        // Dodatkowa walidacja
        if ($normalized !== $state) {
            \Log::info('State was normalized during setState', [
                'original' => $state,
                'normalized' => $normalized
            ]);
        }
        
        \Log::info('AvailabilityGrid setState - PROCESSED', ['state' => $normalized]);
        
        return parent::setState($normalized);
    }

    public function getState(): mixed
    {
        $state = parent::getState();
        
        \Log::info('aAvailabilityGrid getState called', [
            'raw_state' => $state,
            'type' => gettype($state)
        ]);
        
        // Normalizacja stanu przy pobieraniu
        $normalized = $this->normalizeStateValue($state);
        
        \Log::info('AvailabilityGrid getState - FINAL', ['state' => $normalized]);
        
        return $normalized;
    }

    // UPROSZCZONA funkcja normalizacji stanu
    public function normalizeStateValue($state): array
    {
        \Log::info('normalizeStateValue called', ['input' => $state, 'type' => gettype($state)]);
        
        // Jeśli null lub false, zwróć pustą tablicę
        if (is_null($state) || $state === false || $state === '') {
            return [];
        }
        
        // Obsługa JSON string
        if (is_string($state)) {
            // Sprawdź czy to nie jest "[object Object]"
            if (strpos($state, '[object') === 0) {
                \Log::warning('Invalid object string detected');
                return [];
            }
            
            $decoded = json_decode($state, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $state = $decoded;
                \Log::info('Successfully decoded JSON string', ['decoded' => $state]);
            } else {
                \Log::warning('Failed to decode JSON state', [
                    'state' => $state, 
                    'error' => json_last_error_msg()
                ]);
                return [];
            }
        }
        
        // Upewnij się, że state jest array
        if (!is_array($state)) {
            \Log::warning('State is not an array after processing', ['type' => gettype($state)]);
            return [];
        }

        // Walidacja i czyszczenie danych
        $validatedState = [];
        $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        foreach ($state as $day => $hours) {
            if (!is_string($day) || !in_array($day, $validDays)) {
                \Log::warning('Invalid day detected', ['day' => $day]);
                continue;
            }
            
            if (!is_array($hours)) {
                \Log::warning('Hours is not array for day', ['day' => $day, 'hours' => $hours]);
                continue;
            }
            
            // Filtruj i waliduj godziny
            $validHours = [];
            foreach ($hours as $hour) {
                if (is_numeric($hour)) {
                    $hourInt = (int)$hour;
                    if ($hourInt >= 7 && $hourInt <= 19) {
                        $validHours[] = $hourInt;
                    }
                }
            }
            
            if (!empty($validHours)) {
                // Usuń duplikaty i sortuj
                $validHours = array_unique($validHours);
                sort($validHours);
                $validatedState[$day] = $validHours;
            }
        }
        
        \Log::info('State normalized successfully', ['result' => $validatedState]);
        return $validatedState;
    }

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

    // NOWA: Funkcja do formatowania wartości dla zapisania w bazie
    public function getFormattedValueForDatabase(): ?string
    {
        $state = $this->getState();
        return !empty($state) ? json_encode($state) : null;
    }
    
    // NOWA: Metoda do bezpiecznego pobierania stanu dla JavaScript
    public function getStateForJavaScript(): string
    {
        $state = $this->getState();
        return json_encode($state ?: []);
    }
}