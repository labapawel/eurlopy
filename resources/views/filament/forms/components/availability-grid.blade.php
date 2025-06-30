<!-- resources/views/filament/forms/components/availability-grid.blade.php -->
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="space-y-4">
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

        <!-- Przyciski szybkich akcji -->
        <div class="flex items-center">
            <button type="button" class="default-hours-btn" onclick="setDefaultWorkingHours()">
                📅 Pon-Pt 8-16
            </button>
            <button type="button" class="clear-all-btn" onclick="clearAllHours()">
                🗑️ Wyczyść wszystko
            </button>
        </div>

        <div class="availability-grid">
            <!-- Empty corner -->
            <div class="availability-hour"></div>
            
            <!-- Day headers - clickable -->
            @foreach($getDays() as $dayKey => $dayName)
                <div class="availability-header"
                     onclick="toggleDayHours('{{ $dayKey }}')"
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
                                id="checkbox_{{ $dayKey }}_{{ $hour }}"
                                @if(is_array($getState()) && isset($getState()[$dayKey]) && in_array($hour, $getState()[$dayKey] ?? []))
                                    checked
                                @endif
                                class="availability-checkbox"
                                onchange="updateAvailability(this, '{{ $dayKey }}', {{ $hour }})"
                            />
                        </label>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Hidden input for form data -->
        <input type="hidden" id="{{ $getId() }}_data" name="{{ $getName() }}" value="{{$getState() ?? '{}'}}" />
        
        <script>
            // Konfiguracja godzin
            const workingHours = [8, 9, 10, 11, 12, 13, 14, 15, 16];
            const allAvailableHours = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
            const weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            
            function updateAvailability(checkbox, day, hour) {
                const hiddenInput = document.getElementById('{{ $getId() }}_data');
                let data = JSON.parse(hiddenInput.value || '{}');
                
                console.log(data,day);
                

                if (!data[day]) {

                    data[day] = [];
                    console.log(data);
                    
                }
                
                // Update visual state
                const container = checkbox.closest('.availability-checkbox-container');
                
                if (checkbox.checked) {
                    if (!data[day].includes(hour)) {
                        data[day].push(hour);
                    }
                    container.classList.add('checked');
                } else {
                    data[day] = data[day].filter(h => h !== hour);
                    container.classList.remove('checked');
                }
                
                if (data[day] && data[day].length === 0) {
                    delete data[day];
                }
                
                hiddenInput.value = JSON.stringify(data);
                
                @if($isLive())
                    $wire.set('{{ $getStatePath() }}', data);
                @endif
            }
            
            function toggleDayHours(day) {
                const hiddenInput = document.getElementById('{{ $getId() }}_data');
                let data = JSON.parse(hiddenInput.value || '{}');
                
                if (!data[day]) {
                    data[day] = [];
                }
                
                const currentHours = data[day] || [];
                
                // Sprawdź czy ma jakiekolwiek godziny zaznaczone
                const hasAnyHours = currentHours.length > 0;
                
                if (hasAnyHours) {
                    // Jeśli ma jakiekolwiek godziny, wyczyść wszystko
                    data[day] = [];
                    delete data[day];
                } else {
                    // Jeśli nie ma żadnych godzin, ustaw godziny pracy 8-16
                    data[day] = [...workingHours];
                }
                
                // Aktualizuj checkboxy wizualnie
                updateVisualState(day, data[day] || []);
                
                hiddenInput.value = JSON.stringify(data);
                
                @if($isLive())
                    $wire.set('{{ $getStatePath() }}', data);
                @endif
            }
            
            function setDefaultWorkingHours() {
                const hiddenInput = document.getElementById('{{ $getId() }}_data');
                let data = {};
                
                // Ustaw godziny pracy dla dni roboczych
                weekdays.forEach(day => {
                    data[day] = [...workingHours];
                });
                
                // Wyczyść weekend
                data.saturday = [];
                data.sunday = [];
                delete data.saturday;
                delete data.sunday;
                
                // Aktualizuj wszystkie checkboxy wizualnie
                ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                    updateVisualState(day, data[day] || []);
                });
                
                hiddenInput.value = JSON.stringify(data);
                
                @if($isLive())
                    $wire.set('{{ $getStatePath() }}', data);
                @endif
            }
            
            function clearAllHours() {
                const hiddenInput = document.getElementById('{{ $getId() }}_data');
                const data = {};
                
                // Aktualizuj wszystkie checkboxy wizualnie
                ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                    updateVisualState(day, []);
                });
                
                hiddenInput.value = JSON.stringify(data);
                
                @if($isLive())
                    $wire.set('{{ $getStatePath() }}', data);
                @endif
            }
            
            function updateVisualState(day, hours) {
                allAvailableHours.forEach(hour => {
                    const checkbox = document.getElementById(`checkbox_${day}_${hour}`);
                    const container = checkbox?.closest('.availability-checkbox-container');
                    
                    if (checkbox && container) {
                        const shouldBeChecked = hours.includes(hour);
                        checkbox.checked = shouldBeChecked;
                        
                        if (shouldBeChecked) {
                            container.classList.add('checked');
                        } else {
                            container.classList.remove('checked');
                        }
                    }
                });
            }
        </script>
    </div>
</x-dynamic-component>