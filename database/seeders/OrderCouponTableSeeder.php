<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\OrderCoupon;

class OrderCouponTableSeeder extends Seeder
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
            OrderCoupon::insert([
                [
                    'order_id'   => 2,
                    'coupon_id'  => 2,
                    'user_id'    => 3,
                    'discount'   => 5.000000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
}
