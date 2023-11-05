<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use App\Enums\PaymentStatus;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesSummaryResource extends JsonResource
{
    public $info;

    public function __construct($info)
    {
        parent::__construct($info);
        $this->info = $info;
    }

    public function toArray($request)
    {
        return [
            "total_sales"   => $this->info['total_sales'],
            "avg_per_day"   => $this->info['avg_per_day'],
            "per_day_sales" => $this->info['per_day_sales'],
        ];
    }
}
