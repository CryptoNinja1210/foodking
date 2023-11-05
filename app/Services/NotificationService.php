<?php

namespace App\Services;

use App\Http\Requests\NotificationRequest;
use App\Libraries\AppLibrary;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class NotificationService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('notification')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @param NotificationRequest $request
     * @return
     * @throws Exception
     */
    public function update(NotificationRequest $request)
    {
        try {
            AppLibrary::fcmDataBind($request);
            Settings::group('notification')->set($request->validated());
            $this->envService->addData([
                'FCM_SECRET_KEY' => $request->notification_fcm_secret_key
            ]);
            Artisan::call('optimize:clear');
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}