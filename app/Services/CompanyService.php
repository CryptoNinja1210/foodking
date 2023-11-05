<?php

namespace App\Services;


use App\Http\Requests\CompanyRequest;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class CompanyService
{

    public $envService;

    public function __construct()
    {
        $this->envService = new EnvEditor();
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            return Settings::group('company')->all();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(CompanyRequest $request)
    {
        try {
            Settings::group('company')->set($request->validated());
            $this->envService->addData(['APP_NAME' => $request->company_name]);
            Artisan::call('optimize:clear');
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
