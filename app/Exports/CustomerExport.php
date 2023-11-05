<?php

namespace App\Exports;


use App\Enums\Status;
use App\Services\CustomerService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromCollection, WithHeadings
{

    public CustomerService $customerService;
    public PaginateRequest $request;

    public function __construct(CustomerService $customerService, $request)
    {
        $this->customerService = $customerService;
        $this->request         = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $customerArray = [];
        $customers     = $this->customerService->list($this->request);

        foreach ($customers as $customer) {
            $customerArray[] = [
                $customer->name,
                $customer->email,
                $customer->country_code . '' . $customer->phone,
                trans('statuse.' . $customer->status),
            ];
        }
        return collect($customerArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.email'),
            trans('all.label.phone'),
            trans('all.label.status')
        ];
    }
}
