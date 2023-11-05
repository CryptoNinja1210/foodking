<?php

namespace App\Exports;

use App\Libraries\AppLibrary;
use App\Services\ItemService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemsReportExport implements FromCollection, WithHeadings
{

    public ItemService $itemService;
    public PaginateRequest $request;

    public function __construct(ItemService $itemService, $request)
    {
        $this->itemService = $itemService;
        $this->request     = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $itemsReportArray  = [];
        $itemsReportsArray = $this->itemService->list($this->request);

        $total = 0;
        foreach ($itemsReportsArray as $item) {
            $total              += $item->orders->count();
            $itemsReportArray[] = [
                $item->name,
                optional($item->category)->name,
                trans('itemType.' . $item->item_type),
                $item->orders->count()
            ];
        }
        $itemsReportArray[] = [
            trans('all.label.total'),
            '',
            '',
            $total
        ];
        return collect($itemsReportArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.name'),
            trans('all.label.item_category_id'),
            trans('all.label.item_type'),
            trans('all.label.quantity'),
        ];
    }
}
