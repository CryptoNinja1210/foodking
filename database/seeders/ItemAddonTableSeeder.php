<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\ItemAddon;

class ItemAddonTableSeeder extends Seeder
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
            ItemAddon::insert([
                [
                    'item_id'              => 1,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 1,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 2,
                    'addon_item_id'        => 48,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 2,
                    'addon_item_id'        => 49,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 2,
                    'addon_item_id'        => 46,
                    'addon_item_variation' => json_encode((object)['1' => 82]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 3,
                    'addon_item_id'        => 48,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 3,
                    'addon_item_id'        => 49,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 3,
                    'addon_item_id'        => 52,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 4,
                    'addon_item_id'        => 48,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 4,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 5,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 5,
                    'addon_item_id'        => 51,
                    'addon_item_variation' => json_encode((object)['1' => 84]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 6,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 6,
                    'addon_item_id'        => 48,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 7,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 7,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 8,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 8,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 9,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 9,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 10,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 10,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 11,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 11,
                    'addon_item_id'        => 48,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 12,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 12,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 13,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 13,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 14,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 14,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 15,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 15,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 16,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 16,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 17,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 17,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 18,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 18,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 19,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 19,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 20,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 20,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 21,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 21,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 22,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 22,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 23,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 23,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 24,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 24,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 25,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 25,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 26,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 26,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 27,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 27,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 28,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 28,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 29,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 29,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 30,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 30,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 31,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 31,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 32,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 32,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 33,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 33,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 34,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 34,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 35,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 35,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 36,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 36,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 37,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 37,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 38,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 38,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 39,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 39,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 40,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 40,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 41,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 41,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 42,
                    'addon_item_id'        => 55,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
                [
                    'item_id'              => 42,
                    'addon_item_id'        => 53,
                    'addon_item_variation' => json_encode((object)[]),
                    'created_at'           => now(),
                    'updated_at'           => now()
                ],
            ]);
        }
    }
}
