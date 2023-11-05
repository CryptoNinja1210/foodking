<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\TaxType;
use App\Enums\Status;
use App\Models\Tax;

class TaxTableSeeder extends Seeder
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
            Tax::insert([
                [
                    'name'       => 'No-VAT',
                    'code'       => 'VAT-0',
                    'tax_rate'   => 0,
                    'type'       => TaxType::PERCENTAGE,
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name'       => 'VAT',
                    'code'       => 'VAT-5%',
                    'tax_rate'   => 5,
                    'type'       => TaxType::PERCENTAGE,
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name'       => 'VAT',
                    'code'       => 'VAT-10%',
                    'tax_rate'   => 10,
                    'type'       => TaxType::PERCENTAGE,
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name'       => 'GST',
                    'code'       => 'GST-5%',
                    'tax_rate'   => 5,
                    'type'       => TaxType::PERCENTAGE,
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name'       => 'GST',
                    'code'       => 'GST-10%',
                    'tax_rate'   => 10,
                    'type'       => TaxType::PERCENTAGE,
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
}
