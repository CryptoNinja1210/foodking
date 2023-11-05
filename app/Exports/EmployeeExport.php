<?php

namespace App\Exports;


use App\Enums\Status;
use App\Services\EmployeeService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeExport implements FromCollection, WithHeadings
{

    public EmployeeService $employeeService;
    public PaginateRequest $request;

    public function __construct(EmployeeService $employeeService, $request)
    {
        $this->employeeService = $employeeService;
        $this->request         = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $employeeArray = [];
        $employees     = $this->employeeService->list($this->request);
        foreach ($employees as $employee) {
            $employeeArray[] = [
                $employee->name,
                $employee->email,
                $employee->country_code . '' . $employee->phone,
                $employee->roles[0]->name,
                trans('statuse.' . $employee->status),
            ];
        }
        return collect($employeeArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.email'),
            trans('all.label.phone'),
            trans('all.label.role'),
            trans('all.label.status')
        ];
    }
}
