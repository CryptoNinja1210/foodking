<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LanguageFileTextGetRequest;
use App\Http\Requests\LanguageRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\LanguageFileListResource;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Services\LanguageService;
use Exception;
use Illuminate\Http\Request;

class LanguageController extends AdminController
{
    private LanguageService $languageService;

    public function __construct(LanguageService $languageService)
    {
        parent::__construct();
        $this->languageService = $languageService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return LanguageResource::collection($this->languageService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(LanguageRequest $request): \Illuminate\Http\Response|LanguageResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new LanguageResource($this->languageService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Language $language): \Illuminate\Http\Response|LanguageResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new LanguageResource($this->languageService->show($language));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(LanguageRequest $request, Language $language): \Illuminate\Http\Response|LanguageResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new LanguageResource($this->languageService->update($request, $language));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Language $language): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->languageService->destroy($language);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function fileList(Language $language): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return LanguageFileListResource::collection($this->languageService->fileList($language));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function fileText(LanguageFileTextGetRequest $request)
    {
        try {
            $explodeName = explode('.', $request->name);
            if ($explodeName > 0) {
                if ($explodeName[1] == 'json') {
                    $this->languageService->fileText($request);
                } else {
                    return $this->languageService->fileText($request);
                }
            }
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function fileTextStore(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->languageService->fileTextStore($request);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
