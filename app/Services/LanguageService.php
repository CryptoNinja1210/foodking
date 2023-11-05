<?php

namespace App\Services;


use App\Http\Requests\LanguageFileTextGetRequest;
use App\Libraries\AppLibrary;
use Exception;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;


class LanguageService
{
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $method = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            return Language::$method($request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(LanguageRequest $request)
    {
        try {
            if (!file_exists(base_path("resources/js/languages/{$request->code}.json"))) {
                copy(base_path("resources/js/languages/en.json"), base_path("resources/js/languages/{$request->code}.json"));
            }

            if (!file_exists(base_path("lang/{$request->code}"))) {
                mkdir(base_path("lang/{$request->code}"), 0755);
                $files = scandir(base_path("lang/en"));
                if (count($files) > 2) {
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            copy(base_path("lang/en/{$file}"), base_path("lang/{$request->code}/{$file}"));
                        }
                    }
                }
            }

            $language = Language::create($request->validated());
            if ($request->image) {
                $language->addMediaFromRequest('image')->toMediaCollection('language');
            }

            return $language;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(LanguageRequest $request, Language $language): Language
    {
        try {
            $language->update($request->validated());
            if ($request->image) {
                $language->clearMediaCollection('language');
                $language->addMediaFromRequest('image')->toMediaCollection('language');
            }
            return $language;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Language $language): void
    {
        try {
            if (Settings::group('site')->get("site_default_language") != $language->id) {
                if (!env('DEMO') && $language->id !== 1) {
                    AppLibrary::deleteDir(base_path("lang/{$language->code}"));
                    if (file_exists(base_path("resources/js/languages/{$language->code}.json"))) {
                        unlink(base_path("resources/js/languages/{$language->code}.json"));
                    }
                }
                $language->delete();
            } else {
                throw new Exception("Default language not deletable", 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Language $language): Language
    {
        try {
            return $language;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function fileList(Language $language): \Vanilla\Support\Collection|\IlluminateAgnostic\Str\Support\Collection|\IlluminateAgnostic\Collection\Support\Collection|\IlluminateAgnostic\StrAgnostic\Str\Support\Collection|\IlluminateAgnostic\ArrAgnostic\Arr\Support\Collection|\Illuminate\Support\Collection|\IlluminateAgnostic\Arr\Support\Collection
    {
        try {
            $i     = 0;
            $array = [];

            if (file_exists(base_path("resources/js/languages/{$language->code}.json"))) {
                $array[$i] = (object)[
                    'path' => base_path("resources/js/languages/{$language->code}.json"),
                    'name' => "{$language->code}.json"
                ];
                $i++;
            }

            if (file_exists(base_path("lang/{$language->code}"))) {
                $files = scandir(base_path("lang/{$language->code}"));
                if (count($files) > 2) {
                    foreach ($files as $file) {
                        if ($file != '.' && $file != '..') {
                            $array[$i] = (object)[
                                'path' => base_path("lang/{$language->code}/{$file}"),
                                'name' => $file
                            ];
                            $i++;
                        }
                    }
                }
            }
            return collect($array);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    public function fileText(LanguageFileTextGetRequest $request)
    {
        if (file_exists($request->path)) {
            $explodeName = explode('.', $request->name);
            if ($explodeName > 0) {
                if ($explodeName[1] == 'json') {
                    include($request->path);
                } else {
                    return include($request->path);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public function fileTextStore(Request $request): void
    {
        try {
            $file        = fopen($request->x_language_file_path, "rw");
            $fileContent = file_get_contents($request->x_language_file_path);
            foreach ($request->all() as $key => $value) {
                if ($key != 'x_language_file_path' && $key != 'x_language_file_name') {
                    $key = str_replace('_', ' ', $key);
                    if (strpos($fileContent, "'" . $key . "'") !== false) {
                        $fileContent = str_replace("'" . $key . "'", "\"{$value}\"", $fileContent);
                    } elseif (strpos($fileContent, "\"{$key}\"") !== false) {
                        $fileContent = str_replace("\"{$key}\"", "\"{$value}\"", $fileContent);
                    }
                }
            }

            file_put_contents($request->x_language_file_path, $fileContent);
            fclose($file);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
