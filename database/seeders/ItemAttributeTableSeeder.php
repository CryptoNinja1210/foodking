<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use App\Enums\Status;
use App\Models\ItemAttribute;

class ItemAttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public array $attributes = [
        'Size',
        'Quantity Choice',
        'Steak Size',
        'Steak Temperature',
        'Choose a filling',
        'Egg Variation'
    ];

    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            foreach ($this->attributes as $attribute) {
                ItemAttribute::create([
                    'name'   => $attribute,
                    'status' => Status::ACTIVE,
                ]);
            }
        }
    }
}
