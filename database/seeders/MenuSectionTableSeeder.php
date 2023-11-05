<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuSection;

class MenuSectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuSection::insert([
            [
                'name'          => 'Header Section',
                'created_at'       => now(),
                'updated_at'       => now()
            ],
            [
                'name'          => 'Footer Section',
                'created_at'       => now(),
                'updated_at'       => now()
            ],
        ]);
    }
}