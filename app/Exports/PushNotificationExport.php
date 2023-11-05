<?php

namespace App\Exports;


use App\Http\Requests\PaginateRequest;
use App\Services\PushNotificationService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PushNotificationExport implements FromCollection, WithHeadings
{
    public PushNotificationService $pushNotificationService;
    public PaginateRequest $request;

    public function __construct(PushNotificationService $pushNotificationService, $request)
    {
        $this->pushNotificationService = $pushNotificationService;
        $this->request                 = $request;
    }

    public function collection(
    ) : \Vanilla\Support\Collection | \IlluminateAgnostic\Str\Support\Collection | \IlluminateAgnostic\StrAgnostic\Str\Support\Collection | \IlluminateAgnostic\Collection\Support\Collection | \IlluminateAgnostic\ArrAgnostic\Arr\Support\Collection | \Illuminate\Support\Collection | \IlluminateAgnostic\Arr\Support\Collection
    {
        $pushNotificationArray = [];
        $pushNotifications     = $this->pushNotificationService->list($this->request);

        foreach ($pushNotifications as $pushNotification) {
            $pushNotificationArray[] = [
                $pushNotification->title,
                $pushNotification->role_id == 0 ? trans('all.label.all_roles') : $pushNotification?->role?->name,
                $pushNotification->user_id == 0 ? trans('all.label.all_users') : $pushNotification?->customer?->name,
            ];
        }
        return collect($pushNotificationArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.title'),
            trans('all.label.role'),
            trans('all.label.user')
        ];
    }
}
