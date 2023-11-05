<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if($envService->getValue('DEMO')) {
            TimeSlot::insert([
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 0,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "18:00",
                    'day'          => 1,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "18:00",
                    'closing_time' => "23:55",
                    'day'          => 1,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 2,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 3,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 4,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 5,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
                [
                    'opening_time' => "00:00",
                    'closing_time' => "23:45",
                    'day'          => 6,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ],
            ]);
        }
    }
}
