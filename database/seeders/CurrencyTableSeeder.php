<?php

namespace Database\Seeders;


use App\Enums\Ask;
use App\Models\Currency;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;


class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::insert([
            [
                'name' => 'Dollars',
                'symbol' => '$',
                'code' => 'USD',
                'is_cryptocurrency' => Ask::NO,
                'exchange_rate' => 1
            ],
        ]);

        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Currency::insert([
                [
                    'name' => 'Rupee',
                    'symbol' => '₹',
                    'code' => 'INR',
                    'is_cryptocurrency' => Ask::NO,
                    'exchange_rate' => 1
                ],
                [
                    'name' => 'Taka',
                    'symbol' => '৳',
                    'code' => 'BDT',
                    'is_cryptocurrency' => Ask::NO,
                    'exchange_rate' => 1
                ],
                [
                    'name' => 'Naira',
                    'symbol' => '₦',
                    'code' => 'NGN',
                    'is_cryptocurrency' => Ask::NO,
                    'exchange_rate' => 1
                ],
                [
                    'name' => 'Peso',
                    'symbol' => '₱',
                    'code' => 'ARS',
                    'is_cryptocurrency' => Ask::NO,
                    'exchange_rate' => 1
                ],
            ]);
        }
    }
}
