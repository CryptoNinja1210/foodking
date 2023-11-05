<?php

namespace App\Exports;


use App\Enums\Status;
use App\Services\DeliveryBoyService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DeliveryBoyExport implements FromCollection, WithHeadings
{

    public DeliveryBoyService $deliveryBoyService;
    public PaginateRequest $request;

    public function __construct(DeliveryBoyService $deliveryBoyService, $request)
    {
        $this->deliveryBoyService = $deliveryBoyService;
        $this->request            = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $deliveryBoyArray = [];
        $deliveryBoys     = $this->deliveryBoyService->list($this->request);

        foreach ($deliveryBoys as $deliveryBoy) {
            $deliveryBoyArray[] = [
                $deliveryBoy->name,
                $deliveryBoy->email,
                $deliveryBoy->country_code . '' . $deliveryBoy->phone,
                trans('statuse.' . $deliveryBoy->status),
            ];
        }
        return collect($deliveryBoyArray);
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
