<!-- resources/views/filament/forms/components/availability-grid.blade.php -->
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="space-y-4" wire:ignore>
        <style>
            .availability-grid {
                display: grid;
                grid-template-columns: 80px repeat(7, 1fr);
                gap: 4px;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                padding: 16px;
                width: 100%;
            }
            
            .dark {
                .availability-grid {
                    background: rgb(9, 9, 11); /* Dark background */
                    border-color: #374151; /* Dark border */
                }
                .availability-header, .availability-hour, .availability-checkbox-container{
                    background: rgba(255, 255, 255, 0.05); /* Dark background */
                    border-color: #374151; /* Dark border */
                    color: #fff;
                }

                
                .default-hours-btn, .availability-checkbox-container.checked  {
                    background-color: rgb(245, 158, 11); /* Dark mode button color */
                    color: white;

                    &.checked {
                        border-color:rgb(55, 65, 81)
                    }
                }

            }

            .availability-header {
                background: #f9fafb;
                border: 1px solid #6b7280;
                padding: 8px;
                text-align: center;
                font-weight: 600;
                font-size: 12px;
                color: #6b7280;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.15s ease;
                user-select: none;
            }
            
            .availability-header:hover {
                background: #6b7280;
                border-color: #6b7280;
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .availability-header:active {
                background: #bfdbfe;
                transform: translateY(0);
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            }
            
            .availability-hour {
                background: #f9fafb;
                padding: 8px;
                text-align: right;
                font-weight: 500;
                font-size: 12px;
                color: #6b7280;
                border-radius: 4px;
            }
            
            .availability-cell {
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 4px;
            }
            
            .availability-checkbox-container {
                position: relative;
                width: 24px;
                height: 24px;
                background: white;
                border: 2px solid #d1d5db;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            
            .availability-checkbox-container:hover {
                border-color: #10b981;
                background: #f0fdf4;
                transform: scale(1.1);
            }
            
            .availability-checkbox-container.checked {
                background: #10b981;
                border-color: #10b981;
            }
            
            .availability-checkbox-container.checked:hover {
                background: #059669;
                border-color: #059669;
            }
            
            .availability-checkbox-container::after {
                content: '✓';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                font-size: 12px;
                font-weight: bold;
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            
            .availability-checkbox-container.checked::after {
                opacity: 1;
            }
            
            .availability-checkbox {
                position: absolute;
                opacity: 0;
                width: 100%;
                height: 100%;
                margin: 0;
                cursor: pointer;
            }
            
            .day-toggle-hint {
                font-size: 10px;
                color: #3b82f6;
                margin-top: 2px;
                opacity: 0.8;
            }
            
            .default-hours-btn {
                background: #10b981;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                margin-bottom: 12px;
            }
            
            .default-hours-btn:hover {
                background: #059669;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
            }
            
            .clear-all-btn {
                background: #ef4444;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                margin-bottom: 12px;
                margin-left: 8px;
            }
            
            .clear-all-btn:hover {
                background: #dc2626;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
            }
        </style>

        @php
            $componentKey = 'AvailabilityGrid_' . str_replace('.', '_', $getId());
        @endphp

        <!-- Przyciski szybkich akcji -->
        <div class="flex items-center">
            <button type="button" class="default-hours-btn" onclick="window.{{ $componentKey }}.setDefaultWorkingHours()">
                📅 Pon-Pt 8-16
            </button>
            <button type="button" class="clear-all-btn" onclick="window.{{ $componentKey }}.clearAllHours()">
                🗑️ Wyczyść wszystko
            </button>
        </div>

        <div class="availability-grid">
            <!-- Empty corner -->
            <div class="availability-hour"></div>
            
            <!-- Day headers - clickable -->
            @foreach($getDays() as $dayKey => $dayName)
                <div class="availability-header"
                     onclick="window.{{ $componentKey }}.toggleDayHours('{{ $dayKey }}')"
                     title="Kliknij aby zaznaczyć 8-16 lub odznaczyć wszystko">
                    {{ Str::limit($dayName, 3, '') }}
                    <div class="day-toggle-hint">⟲</div>
                </div>
            @endforeach

            <!-- Hours and checkboxes -->
            @foreach($getHours() as $hour => $hourLabel)
                <div class="availability-hour">
                    {{ $hourLabel }}
                </div>
                
                @foreach($getDays() as $dayKey => $dayName)
                    <div class="availability-cell">
                        <label class="availability-checkbox-container 
                                     @if(is_array($getState()) && isset($getState()[$dayKey]) && in_array($hour, $getState()[$dayKey] ?? [])) checked @endif">
                            <input 
                                type="checkbox" 
                                name="{{ $getName() }}[{{ $dayKey }}][]" 
                                value="{{ $hour }}"
                                id="checkbox_{{ str_replace('.', '_', $getId()) }}_{{ $dayKey }}_{{ $hour }}"
                                @if(is_array($getState()) && isset($getState()[$dayKey]) && in_array($hour, $getState()[$dayKey] ?? []))
                                    checked
                                @endif
                                class="availability-checkbox"
                                onchange="window.{{ $componentKey }}.updateAvailability(this, '{{ $dayKey }}', {{ $hour }})"
                            />
                        </label>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Hidden input - kompatybilność z różnymi wersjami Filament -->
        <input type="hidden" 
               wire:model="{{ $getStatePath() }}"
               id="{{ str_replace('.', '_', $getId()) }}_data" 
               value="{{ json_encode($getState() ?: []) }}" />
        
        <script>
            // Zapobieganie wielokrotnej inicjalizacji
            if (!window.{{ $componentKey }}) {
                // Inicjalizacja instancji dla tego konkretnego komponentu
                window.{{ $componentKey }} = (function() {
                    const componentId = '{{ str_replace('.', '_', $getId()) }}';
                    const statePath = '{{ $getStatePath() }}';
                    const hiddenInputId = componentId + '_data';
                    let isInitialized = false;
                    let isUserInteracting = false;
                    
                    // Konfiguracja godzin
                    const workingHours = [8, 9, 10, 11, 12, 13, 14, 15, 16];
                    const allAvailableHours = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
                    const weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                    
                    // Funkcja pomocnicza do pobierania i walidacji danych
                    function getValidatedData() {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (!hiddenInput) {
                            console.warn('Hidden input not found:', hiddenInputId);
                            return {};
                        }
                        
                        let currentValue = hiddenInput.value;
                        console.log('Getting data from hidden input:', currentValue);
                        
                        // Jeśli wartość jest pusta, zwróć pusty obiekt
                        if (!currentValue || currentValue === '' || currentValue === 'null' || currentValue === 'undefined') {
                            console.log('Empty or null value detected, returning empty object');
                            return {};
                        }
                        
                        // Sprawdź czy to nie jest "[object Object]"
                        if (currentValue.startsWith('[object')) {
                            console.log('Invalid object string detected, resetting to empty object');
                            hiddenInput.value = '{}';
                            return {};
                        }
                        
                        try {
                            const data = JSON.parse(currentValue);
                            if (typeof data !== 'object' || data === null || Array.isArray(data)) {
                                console.log('Parsed data is not a valid object, resetting');
                                hiddenInput.value = '{}';
                                return {};
                            }
                            return data;
                        } catch (e) {
                            console.log('JSON parse error, resetting:', e.message);
                            hiddenInput.value = '{}';
                            return {};
                        }
                    }
                    
                    // Funkcja pomocnicza do zapisywania danych
                    function saveData(data) {
                        const hiddenInput = document.getElementById(hiddenInputId);
                        if (!hiddenInput) {
                            console.warn('Hidden input not found for saving:', hiddenInputId);
                            return;
                        }
                        
                        if (typeof data !== 'object' || data === null || Array.isArray(data)) {
                            console.error('saveData: Invalid data type:', typeof data, data);
                            data = {};
                        }
                        
                        try {
                            const jsonString = JSON.stringify(data);
                            console.log('Saving data to hidden input:', jsonString);
                            
                            // Zapisuj tylko jeśli wartość się zmieniła
                            if (hiddenInput.value !== jsonString) {
                                hiddenInput.value = jsonString;
                                
                                // Wyzwól zdarzenie input aby Livewire zauważył zmianę
                                const inputEvent = new Event('input', { bubbles: true });
                                const changeEvent = new Event('change', { bubbles: true });
                                
                                // Dodaj flagę, żeby uniknąć nieskończonych pętli
                                inputEvent.fromAvailabilityGrid = true;
                                changeEvent.fromAvailabilityGrid = true;
                                
                                hiddenInput.dispatchEvent(inputEvent);
                                hiddenInput.dispatchEvent(changeEvent);
                            }
                            
                        } catch (e) {
                            console.error('Error stringifying data:', e, data);
                            hiddenInput.value = '{}';
                        }
                    }
                    
                    function updateVisualState(day, hours) {
                        allAvailableHours.forEach(hour => {
                            const checkbox = document.getElementById(`checkbox_${componentId}_${day}_${hour}`);
                            const container = checkbox?.closest('.availability-checkbox-container');
                            
                            if (checkbox && container) {
                                const shouldBeChecked = hours.includes(hour);
                                checkbox.checked = shouldBeChecked;
                                container.classList.toggle('checked', shouldBeChecked);
                            }
                        });
                    }
                    
                    // Funkcje publiczne
                    return {
                        updateAvailability: function(checkbox, day, hour) {
                            isUserInteracting = true;
                            console.log('updateAvailability called:', { day, hour, checked: checkbox.checked });
                            
                            let data = getValidatedData();
                            
                            if (!data[day]) {
                                data[day] = [];
                            }
                            
                            const container = checkbox.closest('.availability-checkbox-container');
                            
                            if (checkbox.checked) {
                                if (!data[day].includes(hour)) {
                                    data[day].push(hour);
                                    data[day].sort((a, b) => a - b); // Sortuj godziny
                                }
                                container.classList.add('checked');
                            } else {
                                data[day] = data[day].filter(h => h !== hour);
                                container.classList.remove('checked');
                            }
                            
                            if (data[day] && data[day].length === 0) {
                                delete data[day];
                            }
                            
                            console.log('Updated data:', data);
                            saveData(data);
                            
                            setTimeout(() => { isUserInteracting = false; }, 500);
                        },
                        
                        toggleDayHours: function(day) {
                            isUserInteracting = true;
                            console.log('toggleDayHours called:', day);
                            
                            let data = getValidatedData();
                            const currentHours = data[day] || [];
                            const hasAnyHours = currentHours.length > 0;
                            
                            if (hasAnyHours) {
                                delete data[day];
                                updateVisualState(day, []);
                            } else {
                                data[day] = [...workingHours];
                                updateVisualState(day, workingHours);
                            }
                            
                            console.log('Toggled day data:', data);
                            saveData(data);
                            
                            setTimeout(() => { isUserInteracting = false; }, 500);
                        },
                        
                        setDefaultWorkingHours: function() {
                            isUserInteracting = true;
                            console.log('setDefaultWorkingHours called');
                            
                            let data = {};
                            weekdays.forEach(day => {
                                data[day] = [...workingHours];
                            });
                            
                            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                                updateVisualState(day, data[day] || []);
                            });
                            
                            console.log('Default working hours data:', data);
                            saveData(data);
                            
                            setTimeout(() => { isUserInteracting = false; }, 500);
                        },
                        
                        clearAllHours: function() {
                            isUserInteracting = true;
                            console.log('clearAllHours called');
                            
                            const data = {};
                            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                                updateVisualState(day, []);
                            });
                            
                            console.log('Cleared all hours');
                            saveData(data);
                            
                            setTimeout(() => { isUserInteracting = false; }, 500);
                        },
                        
                        // Funkcja inicjalizacji
                        init: function() {
                            if (isInitialized) {
                                console.log('Component already initialized, skipping');
                                return;
                            }
                            
                            if (isUserInteracting) {
                                console.log('User is interacting, postponing init');
                                setTimeout(() => this.init(), 200);
                                return;
                            }
                            
                            const hiddenInput = document.getElementById(hiddenInputId);
                            if (!hiddenInput) {
                                console.warn('Hidden input not found during init:', hiddenInputId);
                                return;
                            }
                            
                            let initialValue = hiddenInput.value;
                            console.log('Initializing AvailabilityGrid component:', componentId);
                            console.log('Initial value from hidden input:', initialValue);
                            console.log('Initial value type:', typeof initialValue);
                            
                            // Sprawdź czy wartość jest poprawna
                            if (!initialValue || initialValue === '' || 
                                initialValue === 'null' || 
                                initialValue === 'undefined' || 
                                initialValue.startsWith('[object')) {
                                
                                console.log('Setting default empty object');
                                hiddenInput.value = '{}';
                                initialValue = '{}';
                            }
                            
                            try {
                                const data = JSON.parse(initialValue);
                                console.log('Parsed initial data:', data);
                                console.log('Parsed data type:', typeof data);
                                
                                if (data && typeof data === 'object' && !Array.isArray(data)) {
                                    console.log('Processing initial data for visual state');
                                    Object.keys(data).forEach(day => {
                                        if (Array.isArray(data[day])) {
                                            console.log(`Setting visual state for ${day}:`, data[day]);
                                            updateVisualState(day, data[day]);
                                        }
                                    });
                                } else {
                                    console.log('Initial data is not a valid object');
                                }
                            } catch (e) {
                                console.log('Error parsing initial data:', e);
                                hiddenInput.value = '{}';
                            }
                            
                            isInitialized = true;
                            console.log('Component initialized successfully');
                        }
                    };
                })();
            }
            
            // Jednokrotna inicjalizacja
            (function() {
                let initAttempts = 0;
                const maxAttempts = 10;
                
                function tryInit() {
                    initAttempts++;
                    const hiddenInput = document.getElementById('{{ str_replace('.', '_', $getId()) }}_data');
                    
                    if (hiddenInput && window.{{ $componentKey }}) {
                        window.{{ $componentKey }}.init();
                        console.log('Component initialized on attempt', initAttempts);
                    } else if (initAttempts < maxAttempts) {
                        setTimeout(tryInit, 100);
                    } else {
                        console.warn('Failed to initialize component after', maxAttempts, 'attempts');
                    }
                }
                
                // Uruchom inicjalizację
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', tryInit);
                } else {
                    tryInit();
                }
            })();
        </script>
    </div>
</x-dynamic-component>