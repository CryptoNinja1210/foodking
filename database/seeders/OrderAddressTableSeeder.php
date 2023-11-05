<?php

namespace Database\Seeders;

use App\Models\OrderAddress;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            OrderAddress::insert([
                [
                    'order_id'   => 1,
                    'user_id'    => 3,
                    'label'      => "Home",
                    'address'    => "Dhaka Bangladesh",
                    'apartment'  => "877, Gulshan 2",
                    'latitude'   => "23.7948",
                    'longitude'  => "90.4143",
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'order_id'   => 2,
                    'user_id'    => 3,
                    'label'      => "Home",
                    'address'    => "Dhaka Bangladesh",
                    'apartment'  => "877, Gulshan 2",
                    'latitude'   => "23.7948",
                    'longitude'  => "90.4143",
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'order_id'   => 3,
                    'user_id'    => 3,
                    'label'      => "Home",
                    'address'    => "Dhaka Bangladesh",
                    'apartment'  => "877, Gulshan 2",
                    'latitude'   => "23.7948",
                    'longitude'  => "90.4143",
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'order_id'   => 4,
                    'user_id'    => 3,
                    'label'      => "Work",
                    'address'    => "Dhaka Bangladesh",
                    'apartment'  => "916, Mirpur 2",
                    'latitude'   => "23.7873",
                    'longitude'  => "90.3514",
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
}
