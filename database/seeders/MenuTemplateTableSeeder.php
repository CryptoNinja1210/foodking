<?php

namespace Database\Seeders;

use App\Models\MenuTemplate;
use Illuminate\Database\Seeder;

class MenuTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuTemplate::insert([
            [
                'name'       => 'Contact Us',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}