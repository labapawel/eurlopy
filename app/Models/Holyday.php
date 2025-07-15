<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Holyday extends Model
{
    protected $fillable = [
        'user_id',
        'holyday_type_id',
        'start_date',
        'end_date',
        'hours',
        'description',
        'approved',
        'paid',
        'active',
    ];

    public function user()
        {
            return $this->belongsTo(User::class);
        }
    public function holydaytype()
        {
            return $this->belongsTo(HolydayType::class, 'holyday_type_id');
        }

    public function calculateVacationHours()
    {
    $user = $this->user;    

    $startDate = Carbon::parse($this->start_date);
    $endDate = Carbon::parse($this->end_date);

    $period = CarbonPeriod::create($this->start_date, $this->end_date);
    
    $holidays = DayOff::whereBetween('date', [$this->start_date, $this->end_date])
        ->pluck('date')
        ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
        ->toArray();
    
    //  dd($user->hours_per_week);    
    // Dekodowanie harmonogramu (które dni pracuje)
    $workSchedule = $user->hours_per_week;
    $dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
       $totalHours = 0;
    $currentDate = $startDate->copy();
    
    while ($currentDate->lte($endDate)) {
        $dateString = $currentDate->format('Y-m-d');
        
        // Pomiń święta
        if (!in_array($dateString, $holidays)) {
            $dayName = $dayNames[$currentDate->dayOfWeek];
            
            // Sprawdź czy pracownik pracuje w ten dzień i policz godziny
            if (isset($workSchedule[$dayName]) && !empty($workSchedule[$dayName])) {
                $totalHours += count($workSchedule[$dayName]);
            }
        }
        
        $currentDate->addDay();
    }
    
    return $totalHours;
    }

    public function save(array $options = [])
    {
        // Ensure start_date and end_date are in the correct format
        if (isset($this->start_date)) {
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if (isset($this->end_date)) {
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }
        $this->hours = $this->calculateVacationHours();

        // dd($this->calculateVacationHours());

        return parent::save($options);
    }
}
