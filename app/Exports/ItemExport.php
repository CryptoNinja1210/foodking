<?php

namespace App\Exports;

use App\Enums\Ask;
use App\Enums\ItemType;
use App\Enums\Status;
use App\Http\Requests\PaginateRequest;
use App\Services\ItemService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemExport implements FromCollection, WithHeadings
{

    public ItemService $itemService;
    public PaginateRequest $request;

    public function __construct(ItemService $itemService, $request)
    {
        $this->itemService = $itemService;
        $this->request            = $request;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $itemArray = [];
        $items     = $this->itemService->list($this->request);

        foreach ($items as $item) {
            $itemArray[] = [
                $item->name,
                $item->price,
                $item->category?->name,
                $item->tax?->name,
                trans('itemType.' . $item->item_type),
                trans('ask.' . $item->is_featured),
                trans('statuse.' . $item->status),
            ];
        }
        return collect($itemArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.name'),
            trans('all.label.price'),
            trans('all.label.item_category_id'),
            trans('all.label.tax_id'),
            trans('all.label.item_type'),
            trans('all.label.is_featured'),
            trans('all.label.status')
        ];
    }
}