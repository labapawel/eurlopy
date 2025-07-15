<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Holidays\Holidays;
use App\Models\DayOff;

class updateDayOff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dayoff {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktualizuje dni wolne';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year') ?? date('Y');
        $this->info("Aktualizacja dni wolnych dla roku: $year");
        $holidays = Holidays::for(country: 'PL', year:$year)->get();

        foreach ($holidays as $holiday) {
            DayOff::updateOrCreate(
                ['date' => $holiday['date']->format('Y-m-d')],
                [
                    'name' => $holiday['name'],
                    'active' => true,
                ]
            );
        }

        $this->info('Dni wolne zostały zaktualizowane.');
        
    }
}
