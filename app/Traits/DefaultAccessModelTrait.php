<?php

namespace App\Traits;


use App\Models\DefaultAccess;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Smartisan\Settings\Facades\Settings;


trait DefaultAccessModelTrait
{
    public function branch()
    {
        if (!App::runningInConsole() && Auth::check()) {
            $access = DefaultAccess::where(['user_id' => Auth::id(), 'name' => 'branch_id'])->first();
            if ($access) {
                return $access->default_id;
            } elseif (Auth::user()->branch_id == 0) {
                return Settings::group('site')->get('site_default_branch');
            } else {
                return Auth::user()->branch_id;
            }
        } else {
            return Settings::group('site')->get('site_default_branch');
        }
    }

    public function setBranch($branchId)
    {
        if (!App::runningInConsole() && Auth::check()) {
            if ($branchId != '0' && ($branchId == '' || $branchId == null)) {
                $branchId = $this->branch();
            } elseif ($branchId == '0' && $branchId == Auth::user()->branch_id) {
                $branchId = 0;
            } else {
                $this->branch();
            }
        } elseif ($branchId != 0) {
            if (App::runningInConsole()) {
                $branchId = $branchId;
            } else {
                $branchId = Settings::group('site')->get('site_default_branch');
            }
        }
        return $branchId;
    }
}
