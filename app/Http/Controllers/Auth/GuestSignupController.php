<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Activity;
use App\Enums\Ask;
use App\Http\Requests\GuestSignupPhoneRequest;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Libraries\AppLibrary;
use App\Services\DefaultAccessService;
use App\Services\MenuService;
use App\Services\PermissionService;
use Carbon\Carbon;
use Exception;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\Role as EnumRole;
use App\Services\OtpManagerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VerifyPhoneRequest;
use Smartisan\Settings\Facades\Settings;

class GuestSignupController extends Controller
{
    private OtpManagerService $otpManagerService;
    public string $token;
    public DefaultAccessService $defaultAccessService;
    public PermissionService $permissionService;
    public MenuService $menuService;

    public function __construct(
        OtpManagerService $otpManagerService,
        MenuService $menuService,
        PermissionService $permissionService,
        DefaultAccessService $defaultAccessService
    ) {
        $this->otpManagerService    = $otpManagerService;
        $this->menuService          = $menuService;
        $this->permissionService    = $permissionService;
        $this->defaultAccessService = $defaultAccessService;
    }


    public function otp(GuestSignupPhoneRequest $request
    ) : \Illuminate\Http\Response | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            $this->otpManagerService->otp($request);
            return response(['status' => true, 'message' => trans("all.message.check_your_phone_for_code")]);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function verify(VerifyPhoneRequest $request)
    {
        try {
            if (Settings::group('site')->get('site_phone_verification') == Activity::DISABLE) {
                $otp = DB::table('otps')->where([
                    ['phone', $request->post('phone')]
                ]);
                $otp?->delete();
                return $this->register(
                    ['code' => $request->code, 'phone' => $request->phone, 'token' => $request->token]
                );
            } elseif ($this->otpManagerService->verify($request) && Settings::group('site')->get(
                    'site_phone_verification'
                ) == Activity::ENABLE) {
                return $this->register(
                    ['code' => $request->code, 'phone' => $request->phone, 'token' => $request->token]
                );
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    private function register($array) : JsonResponse
    {
        $user = User::where(['phone' => $array['phone']])->first();
        if (!$user) {
            $name = "Guest User";
            $user = User::create([
                'name'              => $name,
                'username'          => Str::slug($name) . rand(11111, 99999),
                'phone'             => $array['phone'],
                'country_code'      => $array['code'],
                'branch_id'         => 0,
                'email_verified_at' => Carbon::now()->getTimestamp(),
                'is_guest'          => Ask::YES,
                'password'          => Hash::make(rand(111111, 999999))
            ]);
            $user->assignRole(EnumRole::CUSTOMER);
        }

        if ($user) {
            Auth::guard('web')->loginUsingId($user->id);
            $branchId = Auth::user()->branch_id;
            if (Auth::user()->branch_id == 0) {
                $branchId = Settings::group('site')->get('site_default_branch');
            }

            $this->defaultAccessService->storeOrUpdate(['branch_id' => $branchId]);
            $this->token = $user->createToken('auth_token')->plainTextToken;

            $permission        = PermissionResource::collection($this->permissionService->permission($user->roles[0]));
            $defaultPermission = AppLibrary::defaultPermission($permission);

            return new JsonResponse([
                'message'           => trans('all.message.login_success'),
                'token'             => $this->token,
                'branch_id'         => (int)$user->branch_id,
                'user'              => new UserResource($user),
                'menu'              => MenuResource::collection(collect($this->menuService->menu($user->roles[0]))),
                'permission'        => $permission,
                'defaultPermission' => $defaultPermission,
            ], 201);
        }
        return new JsonResponse([
            'status'  => false,
            'message' => trans('all.message.credentials_invalid'),
        ], 422);
    }
}
