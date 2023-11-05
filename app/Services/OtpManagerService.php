<?php

namespace App\Services;

use App\Events\SendSmsCode;
use Exception;
use App\Models\Otp;
use App\Enums\OtpType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\VerifyPhoneRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OtpManagerService
{

    /**
     * @throws Exception
     */
    public function otp(Request $request) : bool
    {
        try {
            $otp = DB::table('otps')->where([
                ['phone', $request->post('phone')],
                ['code', $request->post('code')],
            ]);

            if ($otp->exists()) {
                $otp->delete();
            }

            if (OtpType::SMS == Settings::group('otp')->get('otp_type') || OtpType::BOTH == Settings::group('otp')->get(
                    'otp_type'
                )) {
                $token = rand(
                    pow(10, (int)Settings::group('otp')->get('otp_digit_limit') - 1),
                    pow(10, (int)Settings::group('otp')->get('otp_digit_limit')) - 1
                );
            } else {
                $token = rand(pow(10, 4 - 1), pow(10, 4) - 1);
            }

            $otp = Otp::create([
                'phone' => $request->phone,
                'code' => $request->code,
                'token' => $token,
                'created_at' => now(),
            ]);

            if (!blank($otp)) {
                SendSmsCode::dispatch(
                    ['phone' => $request->post('phone'), 'code' => $request->post('code'), 'token' => $token]
                );
            }

            return true;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function verify(VerifyPhoneRequest $request) : bool
    {
        try {
            if(env('DEMO')) {
                return true;
            }

            $otp = DB::table('otps')->where([
                ['phone', $request->post('phone')],
                ['token', $request->post('token')],
            ]);
            if ($otp->exists()) {
                $difference = Carbon::now()->diffInSeconds($otp->first()->created_at);
                if ($difference > (int)Settings::group('otp')->get('otp_expire_time') * 60) {
                    throw new Exception(trans('all.message.code_is_expired'), 422);
                } else {
                    $otp->delete();
                    return true;
                }
            } else {
                throw new Exception(trans('all.message.code_is_invalid'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
