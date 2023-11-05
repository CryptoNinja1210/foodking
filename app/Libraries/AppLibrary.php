<?php

namespace App\Libraries;

use App\Enums\AmountType;
use App\Enums\CurrencyPosition;
use App\Models\ProductVariation;
use App\Models\Tax;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Smartisan\Settings\Facades\Settings;

class AppLibrary
{
    public static function date($date, $pattern = null): string
    {
        if (!$pattern) {
            $pattern = env('DATE_FORMAT');
        }
        return Carbon::parse($date)->format($pattern);
    }

    public static function time($time, $pattern = null): string
    {
        if (!$pattern) {
            $pattern = env('TIME_FORMAT');
        }
        return Carbon::parse($time)->format($pattern);
    }

    public static function datetime($dateTime, $pattern = null): string
    {
        if (!$pattern) {
            $pattern = env('TIME_FORMAT') . ', ' . env('DATE_FORMAT');
        }
        return Carbon::parse($dateTime)->format($pattern);
    }

    public static function increaseDate($dateTime, $days, $pattern = null): string
    {
        if (!$pattern) {
            $pattern = env('DATE_FORMAT');
        }
        return Carbon::parse($dateTime)->addDays($days)->format($pattern);
    }

    public static function deliveryTime($dateTime, $pattern = null): string
    {
        if (!$pattern) {
            $pattern = env('TIME_FORMAT');
        }
        $explode = explode('-', $dateTime);
        if (count($explode) == 2) {
            return Carbon::parse(trim($explode[0]))->format($pattern) . ' - ' . Carbon::parse(trim($explode[1]))->format($pattern);
        }
        return '';
    }

    public static function associativeToNumericArrayBuilder($array): array
    {
        $i = 1;
        $buildArray = [];
        if (count($array)) {
            foreach ($array as $arr) {
                if (isset($arr['children'])) {
                    $children = $arr['children'];
                    unset($arr['children']);

                    $arr['parent'] = 0;
                    $buildArray[$i] = $arr;
                    $parentId = $i;
                    $i++;
                    foreach ($children as $child) {
                        $child['parent'] = $parentId;
                        $buildArray[$i] = $child;
                        $i++;
                    }
                } else {
                    $arr['parent'] = 0;
                    $buildArray[$i] = $arr;
                    $i++;
                }
            }
        }
        return $buildArray;
    }

    public static function numericToAssociativeArrayBuilder($array): array
    {
        $i = 0;
        $parentId = null;
        $parentIncrementId = null;
        $buildArray = [];
        if (count($array)) {
            foreach ($array as $arr) {
                if (!$arr['parent']) {
                    $parentId = $arr['id'];
                    $parentIncrementId = $i;
                    $buildArray[$i] = $arr;
                    $i++;
                }

                if ($arr['parent'] == $parentId) {
                    $buildArray[$parentIncrementId]['children'][] = $arr;
                }
            }
        }
        if ($buildArray) {
            foreach ($buildArray as $key => $build) {
                if ($build['url'] == "#" && !isset($build['children'])) {
                    unset($buildArray[$key]);
                }
            }
        }

        return $buildArray;
    }

    public static function permissionWithAccess(&$permissions, $rolePermissions): object
    {
        if ($permissions) {
            foreach ($permissions as $permission) {
                if (isset($rolePermissions[$permission->id])) {
                    $permission->access = true;
                } else {
                    $permission->access = false;
                }
            }
        }
        return $permissions;
    }

    public static function menu(&$menus, $permissions): array
    {
        if ($menus && $permissions) {
            foreach ($menus as $key => $menu) {
                if (isset($permissions[$menu['url']]) && !$permissions[$menu['url']]['access']) {
                    if ($menu['url'] != '#') {
                        unset($menus[$key]);
                    }
                }
            }
        }
        return $menus;
    }

    public static function pluck($array, $value, $key = null, $type = 'object'): array
    {
        $returnArray = [];
        if ($array) {
            foreach ($array as $item) {
                if ($key != null) {
                    if ($type == 'array') {
                        $returnArray[$item[$key]] = strtolower($value) == 'obj' ? $item : $item[$value];
                    } else {
                        $returnArray[$item[$key]] = strtolower($value) == 'obj' ? $item : $item->$value;
                    }
                } elseif ($value == 'obj') {
                    $returnArray[] = $item;
                } elseif ($type == 'array') {
                    $returnArray[] = $item[$value];
                } else {
                    $returnArray[] = $item->$value;
                }
            }
        }
        return $returnArray;
    }

    public static function username($name)
    {
        if ($name) {
            $username = strtolower(str_replace(' ', '', $name)) . rand(1, 999999);
            if (User::where(['username' => $username])->first()) {
                self::username($name);
            }
            return $username;
        }
    }

    public static function name($firstName, $lastName): string
    {
        return $firstName . ' ' . $lastName;
    }

    public static function branchChecking($branch_id): int
    {
        if (Auth::check()) {
            $branch_id = $branch_id ?? null;
            if ($branch_id === null) {
                $branch_id = Settings::group('site')->get('site_default_branch');
            } elseif ($branch_id === 0) {
                if ($branch_id === Auth::user()->branch_id) {
                    $branch_id = 0;
                } else {
                    $branch_id = Auth::user()->branch_id;
                }
            } else {
                $branch_id = Auth::user()->branch_id;
            }
        }
        return $branch_id;
    }

    public static function amountCheck($amount, $attr = 'price'): object
    {
        $response = [
            'status' => true,
            'message' => ''
        ];

        if (!is_numeric($amount)) {
            $response['status'] = false;
            $response['message'] = "This {$attr} must be integer.";
        }

        if ($amount <= 0) {
            if ($response['status'] == false) {
                return (object)$response;
            } else {
                $response['status'] = false;
                $response['message'] = "This {$attr} negative amount not allow.";
            }
        }

        $replaceValue = str_replace('.', '', $amount);
        if (strlen($replaceValue) > 12) {
            if ($response['status'] == false) {
                return (object)$response;
            } else {
                $response['status'] = false;
                $response['message'] = "This {$attr} length can't be greater than 12 digit.";
            }
        }

        if (!preg_match("/^\d{1,10}(\.\d{1,2})?$/", $amount)) {
            if ($response['status'] == false) {
                return (object)$response;
            } else {
                $response['status'] = false;
                $response['message'] = "This {$attr} amount provide invalid.";
            }
        }

        return (object)$response;
    }

    public static function currencyAmountFormat($amount): string
    {
        if (env('CURRENCY_POSITION') == CurrencyPosition::LEFT) {
            return env('CURRENCY_SYMBOL') . number_format($amount, env('CURRENCY_DECIMAL_POINT'), '.', '');
        }
        return number_format($amount, env('CURRENCY_DECIMAL_POINT'), '.', '') . env('CURRENCY_SYMBOL');
    }

    public static function flatAmountFormat($amount): string
    {
        return number_format($amount, env('CURRENCY_DECIMAL_POINT'), '.', '');
    }

    public static function convertAmountFormat($amount): float
    {
        return (float)number_format($amount, env('CURRENCY_DECIMAL_POINT'), '.', '');
    }

    public static function fcmDataBind($request)
    {
        $cdn = public_path("firebase-cdn.txt");
        $textContent = public_path("firebase-content.txt");
        $file = public_path("firebase-messaging-sw.js");
        $content = 'let config = {
        apiKey: "' . $request->notification_fcm_api_key . '",
        authDomain: "' . $request->notification_fcm_auth_domain . '",
        projectId: "' . $request->notification_fcm_project_id . '",
        storageBucket: "' . $request->notification_fcm_storage_bucket . '",
        messagingSenderId: "' . $request->notification_fcm_messaging_sender_id . '",
        appId: "' . $request->notification_fcm_app_id . '",
        measurementId: "' . $request->notification_fcm_measurement_id . '",' . "\n" . ' };' . "\n";
        File::put($file, File::get($cdn) . $content . File::get($textContent));
    }

    public static function defaultPermission($permissions)
    {
        $defaultPermission = (object)[];
        if (count($permissions)) {
            foreach ($permissions as $permission) {
                if ($permission->access) {
                    $defaultPermission = $permission;
                    break;
                }
            }
        }
        return $defaultPermission;
    }

    public static function domain($input)
    {
        $input = trim($input, '/');
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url($input);

        $link = '';
        if (isset($urlParts['port'])) {
            $link .= ':' . $urlParts['port'];
        }

        if (isset($urlParts['path'])) {
            $link .= $urlParts['path'];
        }

        return preg_replace('/^www\./', '', ($urlParts['host'] . $link));
    }

    public static function licenseApiResponse($response)
    {
        $header      = explode(';', $response->getHeader('Content-Type')[0]);
        $contentType = $header[0];
        if ($contentType == 'application/json') {
            $contents = $response->getBody()->getContents();
            $data     = json_decode($contents);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $data;
            }
            return $contents;
        }

        return ['status' => false, 'message' => 'data not found'];
    }


    public static function deleteDir($dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
