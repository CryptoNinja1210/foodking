<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Models\ItemExtra;
use App\Enums\Status;

class ItemExtraTableSeeder extends Seeder
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
            ItemExtra::insert([
                [
                    'item_id'    => 6,
                    'name'       => 'Add Tomato',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 6,
                    'name'       => 'Add Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 6,
                    'name'       => 'Add Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 6,
                    'name'       => 'Add Patty',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 7,
                    'name'       => 'Add Tomato',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 7,
                    'name'       => 'Add Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 7,
                    'name'       => 'Add Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 8,
                    'name'       => 'Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 8,
                    'name'       => 'Bacon',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 8,
                    'name'       => 'BBQ Sauce',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 9,
                    'name'       => 'Add Tomato',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 9,
                    'name'       => 'Add Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 9,
                    'name'       => 'Add Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 9,
                    'name'       => 'Add Patty',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 10,
                    'name'       => 'Add Tomato',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 10,
                    'name'       => 'Add Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 10,
                    'name'       => 'Add Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 10,
                    'name'       => 'Add Patty',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 11,
                    'name'       => 'Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 11,
                    'name'       => 'Bacon',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 11,
                    'name'       => 'BBQ Sauce',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 12,
                    'name'       => 'Vegan Cheddar Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 12,
                    'name'       => 'Vegan American Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 12,
                    'name'       => 'Extra Plant-Based Bacon',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 12,
                    'name'       => 'Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 13,
                    'name'       => 'Vegan Cheddar Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 13,
                    'name'       => 'Vegan American Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 13,
                    'name'       => 'Extra Plant-Based Bacon',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 13,
                    'name'       => 'Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 14,
                    'name'       => 'Vegan Cheddar Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 14,
                    'name'       => 'Vegan American Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 15,
                    'name'       => 'Vegan Cheddar Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 15,
                    'name'       => 'Vegan American Cheese',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 15,
                    'name'       => 'Lettuce',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 26,
                    'name'       => 'Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 26,
                    'name'       => 'Mushrooms',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 27,
                    'name'       => 'Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 27,
                    'name'       => 'Mushrooms',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 28,
                    'name'       => 'Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 28,
                    'name'       => 'Mushrooms',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 29,
                    'name'       => 'Onion',
                    'price'      => '.50',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 29,
                    'name'       => 'Mushrooms',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 31,
                    'name'       => 'Avocado',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 31,
                    'name'       => 'Bacon',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'item_id'    => 31,
                    'name'       => 'Chilli fries',
                    'price'      => '1.00',
                    'status'     => Status::ACTIVE,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]);
        }
    }
}
