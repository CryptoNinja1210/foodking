<?php

namespace App\Services;

use App\Libraries\QueryExceptionLibrary;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AnalyticRequest;
use App\Http\Requests\PaginateRequest;
use App\Models\Analytic;
use Dipokhalder\EnvEditor\EnvEditor;

class AnalyticService
{

    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    protected $analyticsFilter = [
        'name',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Analytic::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->analyticsFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(AnalyticRequest $request)
    {
        try {
            if (!$this->envService->getValue('DEMO')) {
                $analytic = Analytic::create($request->validated());
                return $analytic;
            } else {
                throw new Exception(trans('all.message.feature_disable'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(AnalyticRequest $request, Analytic $analytic)
    {
        try {
            if (!$this->envService->getValue('DEMO')) {
                $analytic->update($request->validated());
                return $analytic;
            } else {
                throw new Exception(trans('all.message.feature_disable'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Analytic $analytic)
    {
        try {
            DB::transaction(function () use ($analytic) {
                $analytic->analyticSections()->delete();
                $analytic->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($exception), $exception->getCode());
        }
    }

    /**
     * @throws Exception
     */
    public function show(Analytic $analytic): Analytic
    {
        try {
            return $analytic;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}