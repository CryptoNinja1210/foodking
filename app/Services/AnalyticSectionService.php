<?php

namespace App\Services;

use Exception;
use App\Models\Analytic;
use App\Models\AnalyticSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\AnalyticSectionRequest;
use Dipokhalder\EnvEditor\EnvEditor;

class AnalyticSectionService
{

    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    protected array $analyticsSectionFilter = [
        'name',
        'data',
        'type'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request, Analytic $analytic)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return AnalyticSection::where(['analytic_id' => $analytic->id])->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->analyticsSectionFilter)) {
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
    public function store(AnalyticSectionRequest $request, Analytic $analytic)
    {
        try {
            if (!$this->envService->getValue('DEMO')) {
                return AnalyticSection::create($request->validated() + ['analytic_id' => $analytic->id]);
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
    public function update(AnalyticSectionRequest $request, Analytic $analytic, AnalyticSection $analyticSection)
    {
        try {

            if (!$this->envService->getValue('DEMO')) {
                DB::transaction(function () use ($request, $analytic, $analyticSection) {
                    if ($analytic->id == $analyticSection->analytic_id) {
                        $analyticSection->update($request->validated());
                    }
                });
                return $analyticSection;
            } else {
                throw new Exception(trans('all.message.feature_disable'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Analytic $analytic, AnalyticSection $analyticSection)
    {
        try {
            DB::transaction(function () use ($analytic, $analyticSection) {
                if ($analytic->id == $analyticSection->analytic_id) {
                    $analyticSection->delete();
                }
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Analytic $analytic, AnalyticSection $analyticSection)
    {
        try {
            if ($analytic->id == $analyticSection->analytic_id) {
                return $analyticSection;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
