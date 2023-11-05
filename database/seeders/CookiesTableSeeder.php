<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Smartisan\Settings\Facades\Settings;
use Illuminate\Database\Seeder;


class CookiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        Settings::group('cookies')->set([
            'cookies_details_page_id' => $envService->getValue('DEMO') ? '1' : '0',
            'cookies_summary'         => $envService->getValue(
                'DEMO'
            ) ? 'This website uses cookies to better understand how visitors use our site, for advertising, and to offer you a more personalized experience. We share information about your use of our site with analytics, social media, and advertising partners in according with our Privacy Statement. You can manage this sharing by selecting the "Cookie Settings" link.' : ''
        ]);
    }
}
