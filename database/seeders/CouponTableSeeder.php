<?php

namespace Database\Seeders;

use App\Enums\TaxType;
use App\Models\Coupon;
use Carbon\Carbon;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class CouponTableSeeder extends Seeder
{
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            $coupons = [
                [
                    'name'             => 'Fairy',
                    'code'             => 'fairy',
                    'discount'         => '7.00',
                    'discount_type'    => TaxType::PERCENTAGE,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(365),
                    'minimum_order'    => '19.00',
                    'maximum_discount' => '99.00',
                    'limit_per_user'   => '5',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
                [
                    'name'             => 'Shake',
                    'code'             => 'shake',
                    'discount'         => '5.00',
                    'discount_type'    => TaxType::FIXED,
                    'start_date'       => now(),
                    'end_date'         => Carbon::now()->addDay(365),
                    'minimum_order'    => '19.00',
                    'maximum_discount' => '5.00',
                    'limit_per_user'   => '5',
                    'created_at'       => now(),
                    'updated_at'       => now()
                ],
            ];

            foreach ($coupons as $coupon) {
                $couponObject = Coupon::create($coupon);
                if (file_exists(
                    public_path('/images/seeder/coupon/' . strtolower(str_replace(' ', '_', $coupon['code'])) . '.png')
                )) {
                    $couponObject->addMedia(
                        public_path(
                            '/images/seeder/coupon/' . strtolower(str_replace(' ', '_', $coupon['code'])) . '.png'
                        )
                    )->preservingOriginal()->toMediaCollection('coupon');
                }
            }
        }
    }
}
