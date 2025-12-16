<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class FacilityScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = ['Collaborative Space',];
        $scheduleSlots = [
            ['day_of_week' => 'Tuesday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day_of_week' => 'Wednesday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day_of_week' => 'Thursday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day_of_week' => 'Friday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day_of_week' => 'Tuesday', 'start_time' => '13:00:00', 'end_time' => '16:00:00'],
            ['day_of_week' => 'Wednesday', 'start_time' => '13:00:00', 'end_time' => '16:00:00'],
            ['day_of_week' => 'Thursday', 'start_time' => '13:00:00', 'end_time' => '16:00:00'],
            ['day_of_week' => 'Friday', 'start_time' => '13:00:00', 'end_time' => '16:00:00'],
        ];

        foreach ($facilities as $facilityName) {
            $facility = Facility::create([
                'facility_name' => $facilityName,
            ]);

        foreach ($scheduleSlots as $slot) {
            Schedule::create([
                'day_of_week' => $slot['day_of_week'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'facility_id' => $facility->facility_id,
            ]);
            }
        }
    }
}