<?php

namespace App\Http\Requests;

use App\Services\InstallerService;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'license_key' => ['required', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function($validator) {
            $installerService = new InstallerService();
            $response         = $installerService->licenseCodeChecker($validator->validated());
            $request          = $validator->validated();
            if (isset($response->status) && $response->status) {
                $envService = new EnvEditor();
                $envService->addData([
                    'MIX_API_KEY' => $request['license_key'],
                ]);
            } else {
                $validator->errors()->add('license_key', $response->message);
            }
        });
    }
}
