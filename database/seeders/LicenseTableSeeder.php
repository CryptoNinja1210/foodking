<?php

namespace Database\Seeders;


use App\Enums\OtpDigitLimit;
use App\Enums\OtpExpireTime;
use App\Enums\OtpType;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;

class LicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        Settings::group('license')->set([
            'license_key' => $envService->getValue('MIX_API_KEY')
        ]);
        if ($envService->getValue('DEMO')) {
            Settings::group('license')->set([
                'license_key' => 'b6d68vy2-m7g5-20r0-5275-h103w73453q120'
            ]);
            $envService->addData(['MIX_API_KEY' => 'b6d68vy2-m7g5-20r0-5275-h103w73453q120']);
            Artisan::call('optimize:clear');
        }
    }
}
