<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HolidayType;

class Urlopy extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        HolidayType::firstOrCreate([
            'name' => 'Urlop wypoczynkowy',
            'code' => 'U',
            'color' => '#10B981', // Green
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop na żądanie',
            'code' => 'UNZ',
            'color' => '#3B82F6', // Blue
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop bezpłatny',
            'code' => 'UB',
            'color' => '#EF4444', // Red
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop okolicznościowy',
            'code' => 'UO',
            'color' => '#8B5CF6', // Purple
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop macierzyński',
            'code' => 'UM',
            'color' => '#F59E0B', // Yellow
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop tacierzyński',
            'code' => 'UT',
            'color' => '#0EA5E9', // Light Blue
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop rodzicielski',
            'code' => 'UR',
            'color' => '#14B8A6', // Teal
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Urlop wychowawczy',
            'code' => 'UW',
            'color' => '#F97316', // Orange
            'active' => true,
        ]);

        HolidayType::firstOrCreate([
            'name' => 'Nieobecność płana (Siła wyższa)',
            'code' => 'NP',
            'color' => '#F97316', // Orange
            'active' => true,
        ]);

    }
}
