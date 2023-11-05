<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Activity;
use App\Enums\Ask;
use App\Http\Requests\SignupPhoneRequest;
use App\Libraries\AppLibrary;
use Carbon\Carbon;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\OtpManagerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VerifyPhoneRequest;
use App\Enums\Role as EnumRole;
use Smartisan\Settings\Facades\Settings;

class SignupController extends Controller
{

    private OtpManagerService $otpManagerService;

    public function __construct(OtpManagerService $otpManagerService)
    {
        $this->otpManagerService = $otpManagerService;
    }

    public function otp(SignupPhoneRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->otpManagerService->otp($request);
            return response(['status' => true, 'message' => trans("all.message.check_your_phone_for_code")]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function verify(VerifyPhoneRequest $request
    ) : \Illuminate\Http\Response | array | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->otpManagerService->verify($request);
            return response(['status' => true, 'message' => trans("all.message.otp_verify_success")], 201);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function register(SignupRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        $flag = false;
        $otp  = DB::table('otps')->where([
            ['phone', $request->post('phone')]
        ]);

        if(env('DEMO')) {
            $flag = true;
        } else {
            if (Settings::group('site')->get('site_phone_verification') == Activity::DISABLE) {
                $otp?->delete();
                $flag = true;
            } else {
                if (!$otp->exists()) {
                    $flag = true;
                }
            }
        }

        if ($flag) {
            $user = User::where(['phone' => $request->post('phone'), 'is_guest' => Ask::YES])->first();
            $name = AppLibrary::name($request->post('first_name'), $request->post('last_name'));
            if ($user) {
                $user->name     = $name;
                $user->username = Str::slug($name);
                $user->email    = $request->post('email');
                $user->password = Hash::make($request->post('password'));
                $user->is_guest = Ask::NO;
                $user->save();
            } else {
                $user = User::create([
                    'name'              => $name,
                    'username'          => Str::slug($name),
                    'email'             => $request->post('email'),
                    'phone'             => $request->post('phone'),
                    'country_code'      => $request->post('country_code'),
                    'branch_id'         => 0,
                    'email_verified_at' => Carbon::now()->getTimestamp(),
                    'is_guest'          => Ask::NO,
                    'password'          => Hash::make($request->post('password'))
                ]);
                $user->assignRole(EnumRole::CUSTOMER);
            }
            return response(['status' => true, 'message' => trans('all.message.register_successfully')], 201);
        }
        return response(['status' => false, 'message' => trans('all.message.code_is_invalid')], 422);
    }
}
