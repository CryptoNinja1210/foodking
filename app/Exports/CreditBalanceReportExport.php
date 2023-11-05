<?php

namespace App\Exports;

use App\Libraries\AppLibrary;
use App\Services\UserService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CreditBalanceReportExport implements FromCollection, WithHeadings
{

    public UserService $userService;
    public PaginateRequest $request;

    public function __construct(UserService $userService, $request)
    {
        $this->userService = $userService;
        $this->request      = $request;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        $creditBalanceReportArray  = [];
        $usersArray = $this->userService->list($this->request);

        foreach ($usersArray as $user) {
            $creditBalanceReportArray[] = [
                $user->name,
                $user->email,
                $user->country_code . '' . $user->phone,
                AppLibrary::flatAmountFormat($user->balance),
            ];
        }
        return collect($creditBalanceReportArray);
    }

    public function headings(): array
    {
        return [
            trans('all.label.name'),
            trans('all.label.email'),
            trans('all.label.phone'),
            trans('all.label.balance')
        ];
    }
}
