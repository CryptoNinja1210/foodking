<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('company')->set([
            'company_name'         => 'FoodKing - Restaurant Food Ordering & Delivery App',
            'company_email'        => 'info@inilabs.net',
            'company_phone'        => '+13333846282',
            'company_website'      => 'https://foodking.dev',
            'company_city'         => 'Mirpur 1',
            'company_state'        => 'Dhaka',
            'company_country_code' => 'BGD',
            'company_zip_code'     => '1216',
            'company_address'      => 'House : 25, Road No: 2, Block A, Mirpur-1, Dhaka 1216'
        ]);

        $envService = new EnvEditor();
        $envService->addData([
            'APP_NAME' => "FoodKing - Restaurant Food Ordering & Delivery App"
        ]);
        Artisan::call('optimize:clear');
    }
}
