<?php

namespace App\Exports;

use App\Libraries\AppLibrary;
use App\Services\TransactionService;
use App\Http\Requests\PaginateRequest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionExport implements FromCollection, WithHeadings
{

    public TransactionService $transactionService;
    public PaginateRequest $request;

    public function __construct(TransactionService $transactionService, $request)
    {
        $this->transactionService = $transactionService;
        $this->request            = $request;
    }

    public function collection() : \Illuminate\Support\Collection
    {
        $transactionArray  = [];
        $transactionsArray = $this->transactionService->list($this->request);

        foreach ($transactionsArray as $transaction) {
            $transactionArray[] = [
                $transaction->transaction_no,
                AppLibrary::datetime($transaction->created_at),
                $transaction->payment_method,
                optional($transaction->order)->order_serial_no,
                $transaction->sign . " " . AppLibrary::flatAmountFormat($transaction->amount),
            ];
        }
        return collect($transactionArray);
    }

    public function headings() : array
    {
        return [
            trans('all.label.transaction_id'),
            trans('all.label.date'),
            trans('all.label.payment_method'),
            trans('all.label.order_serial_no'),
            trans('all.label.amount'),
        ];
    }
}
