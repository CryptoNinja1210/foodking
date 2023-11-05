<?php

namespace Database\Seeders;


use App\Enums\OtpDigitLimit;
use App\Enums\OtpExpireTime;
use App\Enums\OtpType;
use Illuminate\Database\Seeder;
use Smartisan\Settings\Facades\Settings;

class OtpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('otp')->set([
            'otp_type'        => (string)OtpType::BOTH,
            'otp_digit_limit' => (string)OtpDigitLimit::FOUR,
            'otp_expire_time' => (string)OtpExpireTime::TEN,
        ]);
    }
}
