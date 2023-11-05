<?php

namespace Database\Seeders;


use App\Enums\Activity;
use Illuminate\Database\Seeder;
use Smartisan\Settings\Facades\Settings;

class OrderSetupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('order_setup')->set([
            'order_setup_food_preparation_time'        => "30",
            'order_setup_schedule_order_slot_duration' => "30",
            'order_setup_takeaway'                     => Activity::ENABLE,
            'order_setup_delivery'                     => Activity::ENABLE,
            'order_setup_free_delivery_kilometer'      => "2",
            'order_setup_basic_delivery_charge'        => "1",
            'order_setup_charge_per_kilo'              => "1"
        ]);
    }
}
