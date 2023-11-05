<?php

namespace Database\Seeders;

use App\Models\OfferItem;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class OfferItemTableSeeder extends Seeder
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
            OfferItem::insert([
                [
                    'offer_id'   => 1,
                    'item_id'    => 6,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'offer_id'   => 1,
                    'item_id'    => 7,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'offer_id'   => 1,
                    'item_id'    => 8,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'offer_id'   => 2,
                    'item_id'    => 26,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'offer_id'   => 2,
                    'item_id'    => 27,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'offer_id'   => 2,
                    'item_id'    => 28,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
}
